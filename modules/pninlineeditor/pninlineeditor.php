<?php



// Avoid direct access to the file

if (!defined('_PS_VERSION_'))

    exit;





class PnInlineEditor extends Module

{

    private $_html = '';

    private $_postErrors = array();

    private $_moduleName = 'pninlineeditor';



    /*

    ** Construct Method

    **

    */

    public function __construct()

    {

        $this->name = 'pninlineeditor';

        $this->tab = 'administration';

        $this->version = '2.6';

        $this->author = 'PrestaNitro';

        parent::__construct();

        $this->displayName = $this->l('PrestaNitro Inline editor');

        $this->description = $this->l('Edit your products in frontoffice!');

    }



    /*

    ** Install / Uninstall Methods

    **

    */

    public function install()

    {

        $version = _PS_VERSION_;



        if (!parent::install() || !$this->registerHook('displayProductTab'))

            return false;





        // CSS Path

        if (substr($version, 0, 3) === "1.5") {

            Configuration::updateGlobalValue('PN_INLINE_NOMBRE', "#pb-left-column > h1");

            Configuration::updateGlobalValue('PN_INLINE_DESC', "#idTab1");

        } else {

            Configuration::updateGlobalValue('PN_INLINE_NOMBRE', 'h1[itemprop=\'name\']');

            Configuration::updateGlobalValue('PN_INLINE_DESC', ".page-product-box div.rte");

        }

        Configuration::updateGlobalValue('PN_INLINE_REF', "p#product_reference span");

        Configuration::updateGlobalValue('PN_INLINE_PRECIO', "span#our_price_display");

        Configuration::updateGlobalValue('PN_INLINE_DESC_CORTA', "div#short_description_content");

        Configuration::updateGlobalValue('PN_INLINE_CANTIDAD', "span#quantityAvailable");

        Configuration::updateGlobalValue('PN_INLINE_RED_PERCENT', "#reduction_percent_display");

        Configuration::updateGlobalValue('PN_INLINE_RED_AMOUNT', "#reduction_amount_display");

        // Enable / Disable

        Configuration::updateGlobalValue('PN_INLINE_EN_NOMBRE', false);

        Configuration::updateGlobalValue('PN_INLINE_EN_DESC', false);

        Configuration::updateGlobalValue('PN_INLINE_EN_REF', false);

        Configuration::updateGlobalValue('PN_INLINE_EN_PRECIO', false);

        Configuration::updateGlobalValue('PN_INLINE_EN_DESC_CORTA', false);

        Configuration::updateGlobalValue('PN_INLINE_EN_CANTIDAD', false);

        Configuration::updateGlobalValue('PN_INLINE_EN_RED_PERCENT', false);

        Configuration::updateGlobalValue('PN_INLINE_EN_RED_AMOUNT', false);

        // Display w/o taxes

        Configuration::updateGlobalValue('PN_INLINE_IMPUESTOS', true);

        // How the store show big prices: False = 1,000.25 | True = 1.000,25

        Configuration::updateGlobalValue('PN_INLINE_FORMATO', false);







        return true;



    }



    public function uninstall()

    {



        // Uninstall

        if (!parent::uninstall() || !$this->unregisterHook('displayProductTab'))

            return false;



        Configuration::deleteByName('PN_INLINE_NOMBRE');

        Configuration::deleteByName('PN_INLINE_REF');

        Configuration::deleteByName('PN_INLINE_PRECIO');

        Configuration::deleteByName('PN_INLINE_DESC_CORTA');

        Configuration::deleteByName('PN_INLINE_DESC');

        Configuration::deleteByName('PN_INLINE_CANTIDAD');

        Configuration::deleteByName('PN_INLINE_IMPUESTOS');

        Configuration::deleteByName('PN_INLINE_RED_PERCENT');

        Configuration::deleteByName('PN_INLINE_RED_AMOUNT');



        Configuration::deleteByName('PN_INLINE_EN_NOMBRE');

        Configuration::deleteByName('PN_INLINE_EN_DESC');

        Configuration::deleteByName('PN_INLINE_EN_REF');

        Configuration::deleteByName('PN_INLINE_EN_PRECIO');

        Configuration::deleteByName('PN_INLINE_EN_DESC_CORTA');

        Configuration::deleteByName('PN_INLINE_EN_CANTIDAD');

        Configuration::deleteByName('PN_INLINE_EN_RED_PERCENT');

        Configuration::deleteByName('PN_INLINE_EN_RED_AMOUNT');

        Configuration::deleteByName('PN_INLINE_FORMATO');





        return true;



    }



    /* Formulario de configuración */

    private function displayForm()

    {

        global $smarty;



        $this->context->smarty->assign(

            array(

                'PN_INLINE_NOMBRE' => Configuration::get('PN_INLINE_NOMBRE'),

                'PN_INLINE_REF' => Configuration::get('PN_INLINE_REF'),

                'PN_INLINE_PRECIO' => Configuration::get('PN_INLINE_PRECIO'),

                'PN_INLINE_DESC_CORTA' => Configuration::get('PN_INLINE_DESC_CORTA'),

                'PN_INLINE_DESC' => Configuration::get('PN_INLINE_DESC'),

                'PN_INLINE_CANTIDAD' => Configuration::get('PN_INLINE_CANTIDAD'),

                'PN_INLINE_IMPUESTOS' => Configuration::get('PN_INLINE_IMPUESTOS'),

                'PN_INLINE_RED_PERCENT' => Configuration::get('PN_INLINE_RED_PERCENT'),

                'PN_INLINE_RED_AMOUNT' => Configuration::get('PN_INLINE_RED_AMOUNT'),

                'PN_INLINE_EN_NOMBRE' => Configuration::get('PN_INLINE_EN_NOMBRE'),

                'PN_INLINE_EN_DESC' => Configuration::get('PN_INLINE_EN_DESC'),

                'PN_INLINE_EN_REF' => Configuration::get('PN_INLINE_EN_REF'),

                'PN_INLINE_EN_PRECIO' => Configuration::get('PN_INLINE_EN_PRECIO'),

                'PN_INLINE_EN_DESC_CORTA' => Configuration::get('PN_INLINE_EN_DESC_CORTA'),

                'PN_INLINE_EN_CANTIDAD' => Configuration::get('PN_INLINE_EN_CANTIDAD'),

                'PN_INLINE_EN_RED_PERCENT' => Configuration::get('PN_INLINE_EN_RED_PERCENT'),

                'PN_INLINE_EN_RED_AMOUNT' => Configuration::get('PN_INLINE_EN_RED_AMOUNT'),

                'PN_INLINE_FORMATO' => Configuration::get('PN_INLINE_FORMATO')

            )

        );

        return $this->display(__FILE__, 'configuracion.tpl');



    }



    /* Resultados del formulario de configuración */

    public function getContent()

    {



        $output = '';



        if (Tools::isSubmit('submit_inlineeditor')) {

            $PN_INLINE_NOMBRE = Tools::getValue('PN_INLINE_NOMBRE');

            $PN_INLINE_REF = Tools::getValue('PN_INLINE_REF');

            $PN_INLINE_PRECIO = Tools::getValue('PN_INLINE_PRECIO');

            $PN_INLINE_DESC_CORTA = Tools::getValue('PN_INLINE_DESC_CORTA');

            $PN_INLINE_DESC = Tools::getValue('PN_INLINE_DESC');

            $PN_INLINE_CANTIDAD = Tools::getValue('PN_INLINE_CANTIDAD');

            $PN_INLINE_IMPUESTOS = Tools::getValue('PN_INLINE_IMPUESTOS');

            $PN_INLINE_RED_PERCENT = Tools::getValue('PN_INLINE_RED_PERCENT');

            $PN_INLINE_RED_AMOUNT = Tools::getValue('PN_INLINE_RED_AMOUNT');

            $PN_INLINE_EN_NOMBRE = Tools::getValue('PN_INLINE_EN_NOMBRE');

            $PN_INLINE_EN_DESC = Tools::getValue('PN_INLINE_EN_DESC');

            $PN_INLINE_EN_REF = Tools::getValue('PN_INLINE_EN_REF');

            $PN_INLINE_EN_PRECIO = Tools::getValue('PN_INLINE_EN_PRECIO');

            $PN_INLINE_EN_DESC_CORTA = Tools::getValue('PN_INLINE_EN_DESC_CORTA');

            $PN_INLINE_EN_CANTIDAD = Tools::getValue('PN_INLINE_EN_CANTIDAD');

            $PN_INLINE_EN_RED_PERCENT = Tools::getValue('PN_INLINE_EN_RED_PERCENT');

            $PN_INLINE_EN_RED_AMOUNT = Tools::getValue('PN_INLINE_EN_RED_AMOUNT');

            $formato = Tools::getValue('PN_INLINE_FORMATO');

            if($formato=="false") {

                $PN_INLINE_FORMATO = false;

            } else{

                $PN_INLINE_FORMATO = true;

            }

            Configuration::updateValue('PN_INLINE_NOMBRE', $PN_INLINE_NOMBRE);

            Configuration::updateValue('PN_INLINE_REF', $PN_INLINE_REF);

            Configuration::updateValue('PN_INLINE_PRECIO', $PN_INLINE_PRECIO);

            Configuration::updateValue('PN_INLINE_DESC_CORTA', $PN_INLINE_DESC_CORTA);

            Configuration::updateValue('PN_INLINE_DESC', $PN_INLINE_DESC);

            Configuration::updateValue('PN_INLINE_CANTIDAD', $PN_INLINE_CANTIDAD);

            Configuration::updateValue('PN_INLINE_IMPUESTOS', $PN_INLINE_IMPUESTOS);

            Configuration::updateValue('PN_INLINE_RED_PERCENT', $PN_INLINE_RED_PERCENT);

            Configuration::updateValue('PN_INLINE_RED_AMOUNT', $PN_INLINE_RED_AMOUNT);

            Configuration::updateValue('PN_INLINE_EN_NOMBRE', $PN_INLINE_EN_NOMBRE);

            Configuration::updateValue('PN_INLINE_EN_DESC', $PN_INLINE_EN_DESC);

            Configuration::updateValue('PN_INLINE_EN_REF', $PN_INLINE_EN_REF);

            Configuration::updateValue('PN_INLINE_EN_PRECIO', $PN_INLINE_EN_PRECIO);

            Configuration::updateValue('PN_INLINE_EN_DESC_CORTA', $PN_INLINE_EN_DESC_CORTA);

            Configuration::updateValue('PN_INLINE_EN_CANTIDAD', $PN_INLINE_EN_CANTIDAD);

            Configuration::updateValue('PN_INLINE_EN_RED_PERCENT', $PN_INLINE_EN_RED_PERCENT);

            Configuration::updateValue('PN_INLINE_EN_RED_AMOUNT', $PN_INLINE_EN_RED_AMOUNT);

            Configuration::updateValue('PN_INLINE_FORMATO', $PN_INLINE_FORMATO);





            if (isset($errors) && count($errors))

                $output .= $this->displayError(implode('<br />', $errors));

            else

                $output .= $this->displayConfirmation($this->l('Settings updated'));

        }



        $version = _PS_VERSION_;

        return $output . $this->displayForm();



    }





    public function actualizarProducto($id_product, $id_product_attribute, $nombre, $precio, $cantidad, $referencia, $descripcion, $descripcion_corta, $red_amount = null, $red_percent = null, $id_lang = null, $id_shop = null, $id_currency = null)

    {



        if ($id_lang == null) {

            $id_lang = (int)$this->context->cookie->id_lang;

        }

        if ($id_shop == null) {

            $id_shop = (int)$this->context->shop->id;

        }

        if ($id_currency == null) {

            $id_currency = (int)$this->context->cookie->id_currency;

        }



        // El nombre no acepta html, por lo que lo eliminamos

        $nombre = strip_tags($nombre);



        $producto = new Product($id_product, false, null, $id_shop);

        if ($nombre != null && $nombre != '' && Configuration::get('PN_INLINE_EN_NOMBRE'))

            $producto->name[$id_lang] = $nombre;

        if ($referencia != null && $referencia != '' && Configuration::get('PN_INLINE_EN_REF')) {

            $referencia = strip_tags($referencia);

            $producto->reference = $referencia;

        }

        if ($descripcion_corta != null && $descripcion_corta != '' && Configuration::get('PN_INLINE_EN_DESC_CORTA'))

            $producto->description_short[$id_lang] = $descripcion_corta;

        if ($descripcion != null && $descripcion != '' && Configuration::get('PN_INLINE_EN_DESC'))

            $producto->description[$id_lang] = $descripcion;





        if ($precio != null && $precio != '' && Configuration::get('PN_INLINE_EN_PRECIO')) {

            if ($id_product_attribute == null) {





                $producto->price = $precio;





                if ($red_percent != null && $red_percent != '' && $red_percent != 'NaN' && Configuration::get('PN_INLINE_EN_RED_PERCENT')) {

                    $precio_producto = (float)$producto->price;

                    $porcentaje_descuento = (100 - (float)$red_percent)/100;


                    $precioCombinacion = ((float)$precio_producto) / $porcentaje_descuento;

                    $producto->price = $precioCombinacion;

                }



                if ($red_amount != null && $red_amount != '' && $red_amount != 'NaN' && Configuration::get('PN_INLINE_EN_RED_AMOUNT')) {

                    $producto->price += (float)$red_amount;

                }





                $producto->price = round( $producto->price, 6);







            } else {

                $combinacion = new Combination((int)$id_product_attribute);

                $combinacion->price = $precio - $producto->price;



                if ($red_percent != null && $red_percent != '' && $red_percent != 'NaN' && Configuration::get('PN_INLINE_EN_RED_PERCENT')) {

                    $precio_producto = (float)$combinacion->price + (float)$producto->price;
                    $porcentaje_descuento = (100 - (float)$red_percent)/100;
                    $precioConDescuento = ((float)$precio_producto) / $porcentaje_descuento;

                    $precioCombinacion = $precioConDescuento - $producto->price;

                    $combinacion->price = $precioCombinacion;

                }



                if ($red_amount != null && $red_amount != '' && $red_amount != 'NaN' && Configuration::get('PN_INLINE_EN_RED_AMOUNT')) {

                    $combinacion->price += (float)$red_amount;

                }



                $combinacion->price = round($combinacion->price, 6);

                $combinacion->update();

            }

        }

        $producto->update();



        if ($cantidad != null && $cantidad != '')

            StockAvailable::setQuantity((int)$id_product, (int)$id_product_attribute, (int)$cantidad);





        if ($producto == null)

            return "NO";

        else

            return "SAVED";

    }





    public function hookdisplayProductTab($params)

    {



        global $smarty;



        $cookie = new Cookie('psAdmin');

        // Only allows admin to edit

        if ($cookie->id_employee) {





            //Tools::addJS(($this->_path) . 'ckeditor/ckeditor.js');



            $version = _PS_VERSION_;

            if (substr($version, 0, 3) === "1.5") {



                //Tools::addJS(($this->_path) . 'pninlineeditor15.js');

                $this->context->controller->addCSS(($this->_path) . 'views/css/pninlineeditor15.css', 'all');

            } else {

                //Tools::addJS(($this->_path) . 'pninlineeditor16.js');

                $this->context->controller->addCSS(($this->_path) . 'views/css/pninlineeditor16.css', 'all');

            }



            //Tools::addJS(($this->_path) . 'pninlineeditor.js');

            $this->context->controller->addJS(($this->_path) . 'pninlineeditor.js');



            $limite = Configuration::get('PS_PRODUCT_SHORT_DESC_LIMIT');

            if(!isset($limite)||$limite=='') {

                $limite = 400;

            }

            $this->context->smarty->assign(

                array(

                    'PN_INLINE_NOMBRE' => Configuration::get('PN_INLINE_NOMBRE'),

                    'PN_INLINE_REF' => Configuration::get('PN_INLINE_REF'),

                    'PN_INLINE_PRECIO' => Configuration::get('PN_INLINE_PRECIO'),

                    'PN_INLINE_DESC_CORTA' => Configuration::get('PN_INLINE_DESC_CORTA'),

                    'PN_INLINE_DESC' => Configuration::get('PN_INLINE_DESC'),

                    'PN_INLINE_CANTIDAD' => Configuration::get('PN_INLINE_CANTIDAD'),

                    'PN_INLINE_RED_PERCENT' => Configuration::get('PN_INLINE_RED_PERCENT'),

                    'PN_INLINE_RED_AMOUNT' => Configuration::get('PN_INLINE_RED_AMOUNT'),

                    'PN_INLINE_EN_NOMBRE' => Configuration::get('PN_INLINE_EN_NOMBRE'),

                    'PN_INLINE_EN_DESC' => Configuration::get('PN_INLINE_EN_DESC'),

                    'PN_INLINE_EN_REF' => Configuration::get('PN_INLINE_EN_REF'),

                    'PN_INLINE_EN_PRECIO' => Configuration::get('PN_INLINE_EN_PRECIO'),

                    'PN_INLINE_EN_DESC_CORTA' => Configuration::get('PN_INLINE_EN_DESC_CORTA'),

                    'PN_INLINE_EN_CANTIDAD' => Configuration::get('PN_INLINE_EN_CANTIDAD'),

                    'PN_INLINE_EN_RED_PERCENT' => Configuration::get('PN_INLINE_EN_RED_PERCENT'),

                    'PN_INLINE_EN_RED_AMOUNT' => Configuration::get('PN_INLINE_EN_RED_AMOUNT'),

                    'PN_INLINE_IMPUESTOS' => Configuration::get('PN_INLINE_IMPUESTOS'),

                    'PN_INLINE_FORMATO' => Configuration::get('PN_INLINE_FORMATO'),

                    'PN_INLINE_DESC_CORTA_LIMIT' => $limite

                )

            );

            return $this->display(__FILE__, 'pninlineeditor.tpl');

        }





    }





}

