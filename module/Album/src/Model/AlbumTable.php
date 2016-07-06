<?php

namespace Album\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class AlbumTable
{
    private $tableGateway;


    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getAlbum($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if(!$row) {
            throw new RuntimeException(sprintf(
                'Impossible de trouver l\entree avec l\id %d',
                $id
            ));
        }
        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $data = [
            'artist' => $album->artist,
            'title' => $album->title,
        ];

        $id = (int) $album->id;

        if($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if(!$this->getAlbum($id)) {
            throw new RuntimeException(sprintf(
                'Impossible de mettre à jour l\'album avec l\'id %d, il n\'existe pas',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => $id]);
    }
}