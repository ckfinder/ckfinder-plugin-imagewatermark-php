<?php


namespace CKSource\CKFinder\Plugin\ImageWatermark;


use CKSource\CKFinder\CKFinder;
use CKSource\CKFinder\Config;
use CKSource\CKFinder\Event\CKFinderEvent;
use CKSource\CKFinder\Event\FileUploadEvent;
use CKSource\CKFinder\Image;
use CKSource\CKFinder\Plugin\PluginInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageWatermark implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var CKFinder
     */
    protected $app;

    public function setContainer(CKFinder $app)
    {
        $this->app = $app;
    }

    public function getJavaScript()
    {

    }

    public function getDefaultConfig()
    {
        return array(
            'imagePath' => __DIR__ . '/stamp.png',
            'position' => array(
                'right'  => null,
                'bottom' => null,
                'left'   => null,
                'top'    => null
            )
        );
    }

    public function calculatePosition(Image $uploadedImage, Image $watermarkImage)
    {
        $dst_x = $dst_y = 0;

        $position = $this->app['config']->get('ImageWatermark.position');

        if (isset($position['right'])) {
            $position_right = $position['right'];

            if ($position_right === 'center') {
                $dst_x = $uploadedImage->getWidth() / 2 - $watermarkImage->getWidth() / 2;
            } else {
                $dst_x = $uploadedImage->getWidth() - $watermarkImage->getWidth() - (int)$position_right;
            }
        } elseif (isset($position['left'])) {
            $position_left = $position['left'];

            if ($position_left === 'center') {
                $dst_x = $uploadedImage->getWidth() / 2 - $watermarkImage->getWidth() / 2;
            } else {
                $dst_x = (int)$position_left;
            }
        }

        if (isset($position['top'])) {
            $position_top = $position['top'];

            if ($position_top === 'center') {
                $dst_y = $uploadedImage->getHeight() / 2 - $watermarkImage->getHeight() / 2;
            } else {
                $dst_y = (int)$position_top;
            }
        } elseif (isset($position['bottom'])) {
            $position_bottom = $position['bottom'];

            if ($position_bottom === 'center') {
                $dst_y = $uploadedImage->getHeight() / 2 - $watermarkImage->getHeight() / 2;
            } else {
                $dst_y = $uploadedImage->getHeight() - $watermarkImage->getHeight() - (int)$position_bottom;
            }
        }



        return array($dst_x, $dst_y);
    }

    public function addWatermark(FileUploadEvent $event)
    {
        $uploadedFile = $event->getUploadedFile();

        if (Image::isSupportedExtension($uploadedFile->getExtension()) && $uploadedFile->isValidImage()) {
            $uploadedImage = Image::create($uploadedFile->getContents());
            $uploadedImageGD = $uploadedImage->getGDImage();

            $watermarkImagePath = $this->app['config']->get('ImageWatermark.imagePath');

            if(Image::isSupportedExtension(pathinfo($watermarkImagePath, PATHINFO_EXTENSION))) {
                $watermarkImage = Image::create(file_get_contents($watermarkImagePath));
                $watermarkImageGD = $watermarkImage->getGDImage();

                // Calculate position
                list($dst_x, $dst_y) = $this->calculatePosition($uploadedImage, $watermarkImage);

                imagecopy($uploadedImageGD, $watermarkImageGD, $dst_x, $dst_y, 0, 0, $watermarkImage->getWidth(), $watermarkImage->getHeight());

                $uploadedFile->setContents($uploadedImage->getData());

                unset($watermarkImage);
            }

            unset($uploadedImage);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(CKFinderEvent::FILE_UPLOAD_BEFORE_SAVE => 'addWatermark');
    }
}