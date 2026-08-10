<?php
namespace MDJ\Factories;

use MDJ\Interfaces\iView;
use MDJ\View\InfopanelItems\InfopanelImage;
use MDJ\View\InfopanelItems\InfopanelTextBox;
use MDJ\View\InfopanelItems\InfopanelRating;
use MDJ\View\InfopanelItems\InfopanelPdfButton;
use MDJ\View\InfopanelItems\InfopanelEditButton;
use MDJ\View\InfopanelItems\InfopanelDeleteButton;
use MDJ\Interfaces\iFactory;


class InfopanelFactory implements iFactory
{
    public function createItem(array $infopanel_item_info): iView
    {
        switch ($infopanel_item_info['type'])
        {
            case 'image':
                return new InfopanelImage($infopanel_item_info['image']);
            
            case 'textbox':
                return new InfopanelTextBox($infopanel_item_info['author'], $infopanel_item_info['tags']);
            
            case 'rating':
                return new InfopanelRating($infopanel_item_info['article_id'], $infopanel_item_info['rating'], $infopanel_item_info['count']);

            case 'pdf_download':
                return new InfopanelPdfButton($infopanel_item_info['article_id']);

            case 'edit_button':
                return new InfopanelEditButton($infopanel_item_info['article_id']);

            case 'delete_button':
                return new InfopanelDeleteButton($infopanel_item_info['article_id']);

            default:
                throw new \Exception('Unknown infopanel item type: ' . $infopanel_item_info['type']);
        }
    }
}