<?php

namespace Brainvire\Uiforms\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class FileList implements OptionSourceInterface
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function toOptionArray()
    {
        $options = [];

        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $importDirectory = $mediaDirectory->getAbsolutePath('import');

        if ($mediaDirectory->isDirectory($importDirectory)) {
            $files = $mediaDirectory->read($importDirectory);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'xml') {
                    // Check if the file extension is 'xml'
                    $fileValue = str_replace('import/', '', $file);

                    $options[] = [
                        'label' => $fileValue,
                        'value' => $fileValue,
                    ];
                }
            }
        }

        return $options;
    }
}
