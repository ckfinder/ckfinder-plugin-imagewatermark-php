<?php


namespace CKSource\CKFinder\Plugin\ImageWatermark;


use CKSource\CKFinder\CKFinder;
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
        // Returns JavaScript plugin code for CKFinder frontend
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
        $dstX = $dstY = 0;

        $position = $this->app['config']->get('ImageWatermark.position');

        if (isset($position['right'])) {
            $positionRight = $position['right'];

            if ($positionRight === 'center') {
                $dstX = $uploadedImage->getWidth() / 2 - $watermarkImage->getWidth() / 2;
            } else {
                $dstX = $uploadedImage->getWidth() - $watermarkImage->getWidth() - (int)$positionRight;
            }
        } elseif (isset($position['left'])) {
            $positionLeft = $position['left'];

            if ($positionLeft === 'center') {
                $dstX = $uploadedImage->getWidth() / 2 - $watermarkImage->getWidth() / 2;
            } else {
                $dstX = (int)$positionLeft;
            }
        }

        if (isset($position['top'])) {
            $positionTop = $position['top'];

            if ($positionTop === 'center') {
                $dstY = $uploadedImage->getHeight() / 2 - $watermarkImage->getHeight() / 2;
            } else {
                $dstY = (int)$positionTop;
            }
        } elseif (isset($position['bottom'])) {
            $positionBottom = $position['bottom'];

            if ($positionBottom === 'center') {
                $dstY = $uploadedImage->getHeight() / 2 - $watermarkImage->getHeight() / 2;
            } else {
                $dstY = $uploadedImage->getHeight() - $watermarkImage->getHeight() - (int)$positionBottom;
            }
        }

        return array($dstX, $dstY);
    }

    public function addWatermark(FileUploadEvent $event)
    {
        $uploadedFile = $event->getUploadedFile();

        if (Image::isSupportedExtension($uploadedFile->getExtension()) && $uploadedFile->isValidImage()) {
            $uploadedImage = Image::create($uploadedFile->getContents());
            $uploadedImageGD = $uploadedImage->getGDImage();

            $watermarkImagePath = $this->app['config']->get('ImageWatermark.imagePath');

            if (Image::isSupportedExtension(pathinfo($watermarkImagePath, PATHINFO_EXTENSION))) {
                $watermarkImage = Image::create(file_get_contents($watermarkImagePath));
                $watermarkImageGD = $watermarkImage->getGDImage();

                // Calculate position
                list($dstX, $dstY) = $this->calculatePosition($uploadedImage, $watermarkImage);

                imagecopy($uploadedImageGD, $watermarkImageGD, $dstX, $dstY, 0, 0, $watermarkImage->getWidth(), $watermarkImage->getHeight());

                $uploadedFile->setContents($uploadedImage->getData());

                unset($watermarkImage);
            }

            unset($uploadedImage);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(CKFinderEvent::FILE_UPLOAD => 'addWatermark');
    }
}