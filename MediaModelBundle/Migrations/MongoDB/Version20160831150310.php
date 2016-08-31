<?php

namespace OpenOrchestra\ModelBundle\Migrations\MongoDB;

use AntiMattr\MongoDB\Migrations\AbstractMigration;
use Doctrine\MongoDB\Database;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160831150310 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription()
    {
        return "Update storage translation";
    }

    /**
     * @param Database $db
     */
    public function up(Database $db)
    {
        $this->upStorageTranslatedValue($db, 'media', 'alts');
        $this->upStorageTranslatedValue($db, 'media', 'titles');
    }

    /**
     * @param Database $db
     */
    public function down(Database $db)
    {
        $this->downStorageTranslatedValue($db, 'media', 'alts');
        $this->downStorageTranslatedValue($db, 'media', 'titles');
    }

    /**
     * @param Database $db
     * @param string $collection
     * @param string $property
     */
    protected function upStorageTranslatedValue(Database $db, $collection, $property)
    {
        $db->execute('
            db.'.$collection.'.find({"'.$property.'":{$exists:1}}).forEach(function(item) {
                 var property = item.'.$property.';
                 var newProperty = {};
                 for (var i in property) {
                    var element = property[i];
                    var language = element.language;
                    var value = element.value;
                    newProperty[language] = value;
                 }
                 item.'.$property.' = newProperty;

                 db.'.$collection.'.update({_id: item._id}, item);
            });
        ');
    }

    /**
     * @param Database $db
     * @param string $collection
     * @param string $property
     */
    protected function downStorageTranslatedValue(Database $db, $collection, $property)
    {
        $db->execute('
            db.'.$collection.'.find({"'.$property.'":{$exists:1}}).forEach(function(item) {
                 var property = item.'.$property.';
                 var newProperty = {};
                 for (var language in property) {
                    var value = property[language];

                    var element = {};
                    element.language = language;
                    element.value = value;
                    newProperty[language] = element;
                 }
                 item.'.$property.' = newProperty;

                 db.'.$collection.'.update({_id: item._id}, item);
            });
        ');
    }
}
