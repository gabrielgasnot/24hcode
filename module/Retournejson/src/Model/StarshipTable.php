<?php

namespace Retournejson\Model;

use RuntimeException;
use Retournejson\Model\Starship;
use Zend\Db\TableGateway\TableGatewayInterface;

class StarshipTable
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

    public function getStarship($id)
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

    public function saveStarship(Starship $starship)
    {
        $id = (int) $starship->id;

        if ($id === 0) {
            $this->tableGateway->insert($starship->getDataArray());
            return;
        }

        if (! $this->getStarship($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update Starship with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($starship-getDataArray(), ['id' => $id]);
    }

    public function deleteStarship($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}