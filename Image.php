<?php
class Image
{
   const RUTA_IMAGEN = 'images/';
   const RUTA_NUEVA_IMAGEN = 'images_new/';

   private $_image;
   private $_widthImage;
    private $_heightImage;
    private $_imageType;
    private $_quality;
    private $newVarForRebase;
  

    function __construct($image)
    {
        $ruta = $this->getRutaImagen();
        $this->_image = $ruta."/".$image;
        set_error_handler(array(Image,'errorHandler'));
    }

    public static function errorHandler ($errno, $errstr, $errfile, $errline)
    {
            switch ($errno) {
            case E_USER_ERROR:
                echo "<b>Errno</b> [$errno] $errstr<br />\n";
                echo "  Error Fatal en la fila $errline de $errfile";
                echo "Se aborto la ejecucion...<br />\n";
                exit(1);
                break;

            case E_USER_WARNING:
                echo "<b>WARNING</b> [$errno] $errstr<br />\n";
                exit(1);
                break;

            case E_USER_NOTICE:
                echo "<b>NOTICE</b> [$errno] $errstr<br />\n";
                exit(1);
                break;

            case 2:
                echo "No existe la ruta especificada.";
                exit(1);
                break;

            default:
                echo "Error desconocido: [$errno] $errstr<br />\n";
                exit(1);
                break;
            }
    }

    function resizeImage($width=null,$height=null)
    {
        try{
            if ($width === null && $height === null) {
                    $error = 'No esta definido ninguno de los dos parametros necesarios';
                    throw new Exception($error);
            }
            if (TRUE === extension_loaded('imagick')) {
                $valor = $this->processImageWithImagick($width, $height);
                return $valor;
            } else if (TRUE === extension_loaded('gd')) {
                $valor = $this->processImageWithGd($width, $height);
                return $valor;
            } else {
                $error = 'No esta instalado ninguno de los dos modulos necesarios';
                throw new Exception($error);
            }
        }catch(Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    function getImageInfo()
    {
        return  getimagesize($this->_image);
    }

    public static function getRutaImagen()
    {
        return self::RUTA_IMAGEN;
    }

    public static function getRutaNuevaImagen()
    {
        return self::RUTA_NUEVA_IMAGEN;
    }

    function setWidth($width)
    {
        $this->_widthImage=$width;
    }

    function setHeight($height)
    {
        $this->_heightImage=$height;
    }

    function getWidthResize($height)
    {
        $porcentaje=$height/$this->_heightImage;
        return $this->_widthImage*$porcentaje;
    }

    function getHeightResize($width)
    {
        $porcentaje=$width/$this->_widthImage;
        return $this->_heightImage*$porcentaje;
    }

    function setImageType($imageType)
    {
        $this->_imageType=$imageType;
    }

    function createImage()
    {
        if ($this->_imageType==1) {
            $img = imagecreatefromgif($this->_image);
        }
        if ($this->_imageType==2) {
            $img = imagecreatefromjpeg($this->_image);
        }
        if ($this->_imageType==3) {
            $img = imagecreatefrompng($this->_image);
        }
        return $img;
    }

    function processImageWithImagick($width,$height)
    {
        $imageNew = new Imagick($this->_image);
        $info = $imageNew->getImageGeometry();
        $this->setWidth($info['width']);
        $this->setHeight($info['height']);
        if ($width===null) {
            $width = $this->getWidthResize($height);
        } else if ($height===null) {
            $height = $this->getHeightResize($width);
        }
        $imageNew->resizeImage($width, $height);
        $newname = $this->setNewName();
        if (is_dir($this->getRutaNuevaImagen())) {
            imagejpeg($thumb, $newname);
        } else {
            $imageNew->writeImage($newname);
        }
        $imageNew->destroy();
        return $newname;

    }

    public function setNewName()
    {
        return $this->getRutaNuevaImagen().uniqid().'jpg';
    }

    function processImageWithGd($width,$height)
    {
        $info = $this->getImageInfo();
        $this->setWidth($info[0]);
        $this->setHeight($info[1]);
        $this->setImageType($info[2]);
        if ($width===null) {
            $width = $this->getWidthResize($height);
        } else if ($height===null) {
            $height = $this->getHeightResize($width);
        }
        $newImage = $this->createImage();
        $thumb = imagecreatetruecolor($width, $height);
        imagecopyresampled($thumb, $newImage, 0, 0, 0, 0, $width, $height, $this->_widthImage, $this->_heightImage);
        $newname = $this->setNewName();
        try{
            if (is_dir($this->getRutaNuevaImagen())) {
                imagejpeg($thumb, $newname);
            } else {
                $error="No existe la ruta para crear la nueva imagen";
                throw new Exception($error);
            }
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
        return $newname;
    }
}
