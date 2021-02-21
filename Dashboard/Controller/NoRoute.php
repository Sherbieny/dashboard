<?php

/**
 * Copyright © Eslam El-Sherbieny. All rights reserved.
 */

namespace Dashboard\Controller;

/**
 * No route controller
 */
class NoRoute extends AbstractController
{

    public function execute()
    {
        echo "<h4> An Error has occurred, please try again.</h4>";
    }
}
