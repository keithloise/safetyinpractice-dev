<?php

namespace {

    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
    use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

    class Page extends SiteTree
    {
        private static $db = [];

        private static $has_one = [];

        private static $has_many = [
            'Sections' => Section::class
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields(); // TODO: Change the autogenerated stub
            $fields->removeByName(['Content']);

            $gridConfig = GridFieldConfig_RecordEditor::create(9999);
            if ($this->Sections()->Count()) {
                $gridConfig->addComponent(new GridFieldSortableRows('Sort'));
            }
            $gridConfig->addComponent(new GridFieldEditableColumns());
            $gridColumns = $gridConfig->getComponentByType(GridFieldEditableColumns::class);
            $gridColumns->setDisplayFields([
                'SectionWidth' => [
                    'title' => 'Section Width',
                    'callback' => function($record, $column, $grid) {
                        $fields = DropdownField::create($column, $column, SectionWidth::get()->filter('Archived', false)->map('Class','Name'));
                        return $fields;
                    }
                ],
                'Archived' => [
                    'title' => 'Archive',
                    'callback' => function($record, $column, $grid) {
                        return CheckboxField::create($column);
                    }
                ]
            ]);

            $gridField = GridField::create(
                'Sections',
                'Sections',
                $this->Sections(),
                $gridConfig
            );

            $fields->addFieldToTab('Root.Main', $gridField, 'Metadata');

            return $fields;
        }

        public function getPreHeaderMenuItems()
        {
            return PreHeaderMenu::get()->filter('Archived', false)->sort('Sort');
        }

        public function getVisibleSections()
        {
            return $this->Sections()->filter('Archived', false)->sort('Sort');
        }
    }
}
