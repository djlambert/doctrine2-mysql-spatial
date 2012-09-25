<?php

namespace CrEOF\Tests;

use Doctrine\ORM\Query;

abstract class OrmTest extends \Doctrine\Tests\OrmFunctionalTestCase
{
    protected static $_setup = false;

    const POSITION = 'CrEOF\Tests\Fixtures\Position';

    protected function setUp() {
        parent::setUp();
        if (!static::$_setup) {
            $this->_schemaTool->createSchema(array(
                                                  $this->_em->getClassMetadata(self::POSITION),
                                             ));
            static::$_setup = true;
        }
    }

    protected function tearDown()
    {
        parent::tearDown();

        $conn = static::$_sharedConn;

        $this->_sqlLoggerStack->enabled = false;

        $conn->executeUpdate('DELETE FROM Position');

        $this->_em->clear();
    }
}
