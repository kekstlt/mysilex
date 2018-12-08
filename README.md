# mysilex
The silex hook for __construct() controllers method (Silex v2)

#install
composer require kekstlt/mysilex dev-master#v1.0

#usage
$app = new MySilex\MyApplication();

if controller has method init(Application $app)
this method will be called before any controller action method.