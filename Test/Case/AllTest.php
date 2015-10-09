<?php
class AllLibTest extends CakeTestSuite {
    public static function suite() {
        $plugin = 'Ipay';

        $suite = new CakeTestSuite('All Ipay Tests Exclude Integration Test');
        $suite->addTestDirectoryRecursive(APP .DS.'Plugin'.DS.$plugin.DS. 'Test'.DS. 'Case'.DS.'Lib'.DS);
        $suite->addTestDirectoryRecursive(APP .DS.'Plugin'.DS.$plugin.DS. 'Test'.DS. 'Case'.DS.'Model'.DS);
        $suite->addTestDirectoryRecursive(APP .DS.'Plugin'.DS.$plugin.DS. 'Test'.DS. 'Case'.DS.'View'.DS);
        $suite->addTestDirectoryRecursive(APP .DS.'Plugin'.DS.$plugin.DS. 'Test'.DS. 'Case'.DS.'Controller'.DS);
        return $suite;
    }
}