<?php namespace HerzGarlan\Jobs\Models;

use Model;
use Validator;
use ValidationException;
use Input;
use Db;
use Flash;
use BackendAuth;
use HerzGarlan\Inventory\Models\Product;
use HerzGarlan\Config\Models\Rate;
use HerzGarlan\Jobs\Classes\JobsHelper;


/**
 * Model
 */
class DeliveryOrder extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
        'order_date' => 'required|after:now',
        'user' => 'required',
        'addr_from' => 'required',
        'postal_from' => 'required|digits:6|numeric',
        'addr_to' => 'required',
        'postal_to' => 'required|digits:6|numeric',
    ];

    public $customMessages = [
       'order_date.required' => 'The Delivery Date field is required.',
       'order_date.after' => 'The Delivery Date must be a future date.',
       'user.required' => 'The Customer field is required.',
       'addr_from.required' => 'The From Address field is required.',
       'addr_to.required' => 'The Destination Address field is required.',
       'postal_from.required' => 'The From Address postal code field is required.',
       'postal_to.required' => 'The Destination Address postal code field is required.',
       'postal_from.numeric' => 'The From Address Postal code must contain numbers only.',
       'postal_to.numeric' => 'The From Address Postal code must contain numbers only.',
       'postal_from.digits' => 'Invalid format for From Address Postal code.',
       'postal_to.digits' => 'Invalid format for Destination Address Postal code.'
    ];

    public $fillable = [
        'user',
        'user_id',
        'product',
        'product_id',
        'dimension',
        'weight',
    ];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = true;

    protected $dates = ['order_date'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'herzgarlan_jobs_delivery_order';

    public $belongsTo = [
        'user' => ['Rainlab\User\Models\User' , 'table' => 'users', 'order' => 'company asc','conditions' => 'is_activated = 1'],
        'product'  => ['HerzGarlan\Inventory\Models\Product']
    ];

    public $belongsToMany = [
        'rates'       => ['HerzGarlan\Config\Models\Rate', 'table' => 'herzgarlan_jobs_delivery_order_rates', 'delete' => true],
        'rates_count' => ['HerzGarlan\Config\Models\Rate', 'table' => 'herzgarlan_jobs_delivery_order_rates', 'count' => true]
    ];

    public $attachMany = [
        'photos' => ['System\Models\File', 'delete' => true]
    ];

    public function getProductOptions()
    {
        return Product::getNameList($this->user_id);
    }

    public function filterFields($fields, $context = null)
    {

        // No selected product
        if (empty($this->product_id)) {
            $fields->product_info->hidden = false;
            $fields->dimension->value = empty($this->product_info) ? '' : $this->dimension;
            $fields->weight->value = empty($this->product_info) ? '' : $this->weight;
        }
        else
        {
            $do = DeliveryOrder::where('id',$this->id)->get();
            $product = Product::where('id', $this->product_id)->get();
            $fields->product_info->hidden = true;

            // if product is not changed
            if( count($do) > 0 )
            {
                if($do[0]['product_id'] == $this->product_id){
                    $fields->dimension->value = $do[0]['dimension'];
                    $fields->weight->value = $do[0]['weight'];
                    $fields->tracking_no->value = $do[0]['tracking_no'];
                }
                else
                {
                    $fields->dimension->value = $product[0]['dimension'];
                    $fields->weight->value = $product[0]['weight'];
                }
            }
            else
            {   
                $fields->dimension->value = $product[0]['dimension'];
                $fields->weight->value = $product[0]['weight'];
            }
       }
    
    }

    /**
     * @Events.
     */

    public function afterValidate()
    {
        $inputs = Input::get('DeliveryOrder');
        $rates = is_array( $inputs['rates'] ) ? true : false;

        if(!$rates){
            throw new ValidationException(['rates' => 'Type of Service is required']);
        }

        if(!empty($inputs['order_date']))
        {
            // check the selected date against Blocked Dates config
            $isBlockedDate = JobsHelper::isBlockedDate($this->order_date);
            if($isBlockedDate){
                throw new ValidationException(['order_date' => 'The selected delivery date and time is blocked please select another date.']);
            }
        }

        if(!empty($inputs['addr_from'] && $inputs['addr_to']))
        {
            // Make sure Product or Product info is not both empty
            if( empty($this->product_id) AND empty($inputs['product_info']) )
            {
                throw new ValidationException(['product_info' => 'Please select a Product or fill up the Product Info field.']);
            }
        }

        $sessionKey = uniqid('session_key', true);
        $deliveryOrder = DeliveryOrder::find(1);

        $fileFromPost = Input::file('photos');
        if ($fileFromPost) {
            $model->rules = [
                'photos'  => 'mimes:jpeg,bmp,png,gif'
            ];
            $deliveryOrder->photos()->create(['data' => $fileFromPost], $sessionKey);
        }

    }

    public function beforeCreate()
    {
        $backend_user = BackendAuth::getUser();
        $this->backend_user_id = $backend_user->id;
        $this->tracking_no = strtoupper( str_random(12) );
    }

    public function beforeDelete()
    {
        Db::table('herzgarlan_jobs_delivery_order_rates')->where('delivery_order_id', '=', $this->id)->delete();
    }

    public function afterDelete()
    {
        if( !empty($this->photos) ){
            foreach ($this->photos as $photo) {
                $photo->delete();
            }
        }
    }


    public function afterCreate()
    {
        Flash::success('Delivery order has been created successfully.');
    }

    public function afterSave()
    {
        Flash::success('Delivery order has been updated successfully.');
    }

}