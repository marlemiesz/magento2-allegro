<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Competition extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('allegro_competition_auctions', 'entity_id');
    }




}
