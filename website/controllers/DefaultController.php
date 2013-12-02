<?php

class DefaultController extends Website_Controller_Action
{

    public function preDispatch()
    {
        $this->enableLayout();
    }

    public function homeAction()
    {

    }

	public function wysiwygAction ()
    {

	}

    public function artistAction ()
    {
        $artistKey = $this->_getParam("artist");
        $artists = new Object_Artist_List();
        $artists->setCondition("o_key = '" . $artistKey . "'");

        $this->view->artist = current($artists->getObjects());
    }

    public function artistsAction()
    {
        $artists = new Object_Artist_List();
        $artists->setOrder("name");


        $artists = $artists->getObjects();

        usort($artists, function($a, $b){
            $aLast = end(explode(' ', $a->getName()));
            $bLast = end(explode(' ', $b->getName()));

            return strcasecmp($aLast, $bLast);
        });

        $this->view->artists = $artists;
    }

    public function aboutAction()
    {
        $newsletter = $this->getRequest()->getParam("newsletter");
        if($newsletter){
            mail("alex@slateprojects.com", "Newsletter subscription", "<p>First Name: {$newsletter['first_name']}</p><p>Last Name: {$newsletter['last_name']}</p><p>Email: {$newsletter['email']}</p>");
            $this->view->subscribed = true;
        }

    }
}
