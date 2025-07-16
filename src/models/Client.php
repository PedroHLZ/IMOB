<?php

namespace App\Models;

use PDO;

class Client
{
    public static function all(PDO $pdo)
    {
        $stmt = $pdo->query("SELECT * FROM clients");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create(PDO $pdo, $data)
    {
        $stmt = $pdo->prepare("INSERT INTO clients (
            name, cpf, phone, email, birthdate, civil_status, broker, formal_income, informal_income, approval_status, 
            property_value, financed_value, subsidy_value, total_value, observation,
            program_mcmv, fgts,
            dependent_name, dependent_cpf, dependent_birthdate, dependent_relationship
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        return $stmt->execute([
            $data['name'],
            $data['cpf'],
            $data['phone'],
            $data['email'],
            $data['birthdate'],
            $data['civil_status'],
            $data['broker'],
            $data['formal_income'],
            $data['informal_income'],
            $data['approval_status'],
            $data['property_value'],
            $data['financed_value'],
            $data['subsidy_value'],
            $data['total_value'],
            $data['observation'],
            $data['program_mcmv'],
            $data['fgts'],
            $data['dependent_name'],
            $data['dependent_cpf'],
            $data['dependent_birthdate'],
            $data['dependent_relationship'],
        ]);
    }

    public static function update(PDO $pdo, $id, $data)
    {
        $stmt = $pdo->prepare("UPDATE clients SET 
            name = ?, 
            cpf = ?, 
            phone = ?, 
            email = ?, 
            birthdate = ?, 
            civil_status = ?, 
            broker = ?, 
            formal_income = ?, 
            informal_income = ?, 
            approval_status = ?, 
            property_value = ?, 
            financed_value = ?,
            subsidy_value = ?,
            total_value = ?,
            observation = ?,
            program_mcmv = ?,
            fgts = ?,
            dependent_name = ?,
            dependent_cpf = ?,
            dependent_birthdate = ?,
            dependent_relationship = ?
            WHERE id = ?");
        $result = $stmt->execute([
            $data['name'],
            $data['cpf'],
            $data['phone'],
            $data['email'],
            $data['birthdate'],
            $data['civil_status'],
            $data['broker'],
            $data['formal_income'],
            $data['informal_income'],
            $data['approval_status'],
            $data['property_value'],
            $data['financed_value'],
            $data['subsidy_value'],
            $data['total_value'],
            $data['observation'],
            $data['program_mcmv'],
            $data['fgts'],
            $data['dependent_name'],
            $data['dependent_cpf'],
            $data['dependent_birthdate'],
            $data['dependent_relationship'],
            $id
        ]);
        if (!$result) {
            error_log('Erro ao executar UPDATE em Client.php: ' . print_r($stmt->errorInfo(), true));
            error_log('Dados enviados: ' . print_r($data, true) . ' | ID: ' . $id);
        }
        return $result;
    }

    public static function delete(PDO $pdo, $id)
    {
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
