<?php

namespace Fourmi\Tools;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class CicadaTable
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

    public function fetchAllNotDone()
    {
        return $this->tableGateway->select(['done' => false]);
    }

    public function get($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function getByTrack($trackId)
    {
        $rowset = $this->tableGateway->select(['track_id' => $trackId]);
        $row = $rowset->current();
        if (! $row) {
            return null;
        }

        return $row;
    }

    public function saveMemory(CicadaMemory $memo)
    {
        $data = [
            'track_id' => $memo->track_id,
            'done'  => $memo->done,
        ];

        $id = (int) $memo->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->get($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteCicadaMemory($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}