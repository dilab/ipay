# iPay88 Plugin for CakePHP
iPay88 is a leading regional Payment Gateway Provider in South East Asia. 

## Requirements
+ CakePHP version: 2.5.x


## Installation 

### Using Git
``` git clone git@github.com:dilab/ipay.git Ipay ```

### Using Composer
``` composer require dilab/ipay ```


## Usage

### Create Plugin Database
```
cake schema create --plugin ipay
```

### Supply iPay88 Merchant Info
+ Copy ipay_config.php to App/Config folder
``` cp app/Plugin/Ipay/Config/ipay_config_sample.php app/Config/ipay_config.php  ```

+ Open app/Config/ipay_config.php and supply correct information for both **merchantKey** and **merchantCode**. Leave rest of info intact. 


### Create Event Listener
Ipay.IpayResponse model fires two events below, which you can use to add your business logic. 

+ **Model.IpayResponse.afterValidResponse**: 
This event is fired when a valid response is sent from iPay88.
It checks **status** and **signature** fields. 

+ **Model.IpayResponse.afterSuccessResponse**: 
This event fires only if event above also happens. 
It re-query iPay88 server to check if it is valid payment. 
 
**Model.IpayResponse.afterSuccessResponse** should be used to identify a successful payment. 

Ipay Plugin comes with backend post by default, it will use this event listener to process 

#### Sample 
In file **app/Controller/AppController.php**:
 
 
```
public function beforeFilter()
{
  $this->loadModel('Ipay.IpayResponse');
  $callback = array($this, 'ipaySuccessResponseCallBack');
  $this->IpayResponse->getEventManager()->attach(
          $callback,
          'Model.IpayResponse.afterSuccessResponse',
          array('passParams' => true)
  );
}

public function ipaySuccessResponseCallBack($id)
{
  // Process your order with your business logic
  // Use $id to get the order information from ipay_repsonses table
}
```

### Create iPay88 Form using Helper 
You can use iPay88 to create the a iPay88 payment form, you should always the helper, 
because it takes care of signature creation. 

#### Sample

```
$data = array(
    'RefNo' => 123,
    'Amount' => 100.00,
    'Currency' => 'USD',
    'ProdDesc' => 'Product',
    'UserName' => 'test user',
    'UserEmail' => 'test@gmail.com',
    'UserContact' => '123',
    'ResponseURL' => 'http://domain/controller/response_handler',
);

echo $this->Ipay->button($data);
             
```

### Process Response using Component
In your response action, simply call ```Ipay.processPaymentResponse();```, it will take care of all the backend process. 

#### Sample

```
public response_handler() 
{
    // Your other code
    
    
    $this->Ipay->processPaymentResponse();

}

```

## Testing

### Unit Test
``` cake test Ipay all ```

### Integration Test
``` cake test Ipay Integration/IpayRequeryIntegration ```

(Do not run this test as part of your Unit Test as it will send HTTP request to iPay88 server.)

## Support
Please use Github Issues to report bugs/issues. 

## License
Licensed under The MIT License
Redistributions of files must retain the above copyright notice.

## Author
Xu Ding

https://github.com/dilab