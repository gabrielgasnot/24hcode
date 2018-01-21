<?php

namespace Fourmi\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class Jwt_TokenTable
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

    public function getToken($id)
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

    public function getTokenByEmail($email)
    {
        $rowset = $this->tableGateway->select(['email' => $email]);
        $row = $rowset->current();
        if (! $row) {
            return null;
        }

        return $row;
    }

    public function saveToken(Jwt_Token $token)
    {
        $data = [
            'email' => $token->email,
            'token'  => $token->token,
        ];

        $id = (int) $token->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getToken($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteToken($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}