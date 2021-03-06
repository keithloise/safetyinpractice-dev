<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\HiddenField;
    use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
    use SilverStripe\Forms\ReadonlyField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\DataObject;

    class SliderItem extends DataObject
    {
        private static $default_sort = 'Sort';

        private static $db = [
            'Name'       => 'Varchar',
            'Content'    => 'HTMLText',
            'ContentPos' => 'Varchar',
            'Archived'   => 'Boolean',
            'Sort'       => 'Int'
        ];

        private static $has_one = [
            'Parent'      => Slider::class,
            'SliderImage' => Image::class
        ];

        private static $owns = [
            'SliderImage'
        ];

        private static $summary_fields = [
            'Name',
            'SliderImage.CMSThumbnail' => 'Slider image',
            'Status'
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields(); // TODO: Change the autogenerated stub
            $fields->removeByName('ParentID');
            $fields->addFieldToTab('Root.Main', ReadonlyField::create('ParentRO', 'Parent', $this->Parent()->Title));
            $fields->addFieldToTab('Root.Main', TextField::create('Name'));
            $fields->addFieldToTab('Root.Main', UploadField::create('SliderImage')
                ->setFolderName('Sections/Slider'));
            $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Content'));
            $fields->addFieldToTab('Root.Main', DropdownField::create('ContentPos', 'Content position',
                array(
                    'left'  => 'Left',
                    'center'=> 'Center',
                    'right' => 'Right'
                )
            ));
            $fields->addFieldToTab('Root.Main', CheckboxField::create('Archived'));
            $fields->addFieldToTab('Root.Main', HiddenField::create('Sort'));
            return $fields;
        }

        public function getStatus()
        {
            if($this->Archived == 1) return _t('GridField.Archived', 'Archived');
            return _t('GridField.Live', 'Live');
        }
    }
}
