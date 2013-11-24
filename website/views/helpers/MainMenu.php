<?php

/**
 * @copyrights Byng Systems
 * @author Michal Maszkiewicz
 * @since 03/07/12
 */

class Zend_View_Helper_MainMenu extends Zend_View_Helper_Abstract
{
    private function _renderMainMenu()
    {

    }

    public function mainMenu()
    {
        /* @var $pages Document_Page[] */
        $pages = Document::getConcreteById(1)->getChilds();

        foreach( $pages as $page )
        {
            /** @var $doc Document_Page */
            $doc = $this->view->document;;
            do{
                if($doc->getId() == $page->getId())
                {
                    $active = $page->getId();
                }
                $doc = $doc->getParent();
            } while($doc);
        }

        return $this->view->partial("navigation/main.php", array("pages" => $pages, "active" => $active));
    }
}
