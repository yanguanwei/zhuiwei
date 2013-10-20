<?php

namespace Youngx\Bundle\ZhuiweiBundle\Controller;

use Youngx\Bundle\ArchiveBundle\Module\NewsModule\Entity\NewsEntity;
use Youngx\MVC\Context;

class FrontController
{
    public function platformAction(Context $context, NewsEntity $news = null)
    {
        if (!$news) {
            $news = $context->repository()->load('news', 6);
        }

        return $context->renderableResponse()->setFile('platform.html.yui@Zhuiwei')
                ->addVariables(array(
                    'news' => $news,
                    'archive' => $news->getArchive()
                ));
    }
}