<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\ResourceModel\Collection;

use Macopedia\Allegro\Model\Competition as Competition;
use Macopedia\Allegro\Model\ResourceModel\Competition as Resource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Competition::class, Resource::class);
    }
}
