<?php

namespace App\Controllers;

use App\Models\Client;

class ClientController
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $clients = Client::all($this->pdo);
        require __DIR__ . '/../views/clients/index.php';
    }

    public function create()
    {
        // Busca todos os corretores
        $stmt = $this->pdo->query("SELECT * FROM brokers ORDER BY name ASC");
        $brokers = $stmt->fetchAll();

        require __DIR__ . '/../views/clients/create.php';
    }

    public function store()
    {
        if (!isset($_POST['name'])) {
            $_SESSION['error'] = 'Dados do formulário não enviados.';
            header('Location: /clients/create');
            exit;
        }
        $data = [
            'name' => $_POST['name'] ?? '',
            'cpf' => $_POST['cpf'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'birthdate' => $_POST['birthdate'] ?? '',
            'civil_status' => $_POST['civil_status'] ?? '',
            'broker' => $_POST['broker'] ?? '',
            'formal_income' => $_POST['formal_income'] ?? '',
            'informal_income' => $_POST['informal_income'] ?? '',
            'approval_status' => $_POST['approval_status'] ?? '',
            'property_value' => $_POST['property_value'] ?? '',
            'financed_value' => $_POST['financed_value'] ?? '',
            'subsidy_value' => $_POST['subsidy_value'] ?? '',
            'total_value' => $_POST['total_value'] ?? '',
            'observation' => $_POST['observation'] ?? '',
            'program_mcmv' => $_POST['program_mcmv'] ?? '',
            'fgts' => $_POST['fgts'] ?? '',
            'dependent_name' => $_POST['dependent_name'] ?? '',
            'dependent_cpf' => $_POST['dependent_cpf'] ?? '',
            'dependent_birthdate' => $_POST['dependent_birthdate'] ?? '',
            'dependent_relationship' => $_POST['dependent_relationship'] ?? '',
        ];

        if (Client::create($this->pdo, $data)) {
            // Recupera o último ID inserido
            $clientId = $this->pdo->lastInsertId();
            // Cria a pasta do cliente
            $uploadDir = __DIR__ . '/../../public/uploads/cliente_' . $clientId;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            // Processa uploads
            if (!empty($_FILES['attachments']['name'][0])) {
                foreach ($_FILES['attachments']['tmp_name'] as $idx => $tmpName) {
                    $name = $_FILES['attachments']['name'][$idx];
                    $dest = $uploadDir . '/' . basename($name);
                    move_uploaded_file($tmpName, $dest);
                }
            }
            $_SESSION['success'] = 'Cliente cadastrado com sucesso!';
            header('Location: /clients');
            exit;
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar cliente.';
            header('Location: /clients/create');
            exit;
        }
    }

    public function show($id)
    {
        if (!$id) {
            $_SESSION['error'] = 'ID não informado.';
            header('Location: /clients');
            exit;
        }
        $client = Client::find($this->pdo, $id);
        if (!$client) {
            $_SESSION['error'] = 'Cliente não encontrado.';
            header('Location: /clients');
            exit;
        }
        require __DIR__ . '/../views/clients/show.php';
    }

    public function edit($id)
    {
        if (!$id) {
            $_SESSION['error'] = 'ID não informado.';
            header('Location: /clients');
            exit;
        }
        $client = Client::find($this->pdo, $id);
        if (!$client) {
            $_SESSION['error'] = 'Cliente não encontrado.';
            header('Location: /clients');
            exit;
        }

        // Busca todos os corretores
        $stmt = $this->pdo->query("SELECT * FROM brokers ORDER BY name ASC");
        $brokers = $stmt->fetchAll();

        require __DIR__ . '/../views/clients/edit.php';
    }

    public function update($id)
    {
        if (!$id) {
            $_SESSION['error'] = 'ID não informado.';
            header('Location: /clients');
            exit;
        }
        $data = [
            'name' => $_POST['name'] ?? '',
            'cpf' => $_POST['cpf'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'birthdate' => $_POST['birthdate'] ?? '',
            'civil_status' => $_POST['civil_status'] ?? '',
            'broker' => $_POST['broker'] ?? '',
            'formal_income' => $_POST['formal_income'] ?? '',
            'informal_income' => $_POST['informal_income'] ?? '',
            'approval_status' => $_POST['approval_status'] ?? '',
            'property_value' => $_POST['property_value'] ?? '',
            'financed_value' => $_POST['financed_value'] ?? '',

            // CAMPOS DO DEPENDENTE:
            'dependent_name' => $_POST['dependent_name'] ?? '',
            'dependent_cpf' => $_POST['dependent_cpf'] ?? '',
            'dependent_birthdate' => $_POST['dependent_birthdate'] ?? '',
            'dependent_relationship' => $_POST['dependent_relationship'] ?? '',
            'subsidy_value' => $_POST['subsidy_value'] ?? '',
            'total_value' => $_POST['total_value'] ?? '',
            'program_mcmv' => $_POST['program_mcmv'] ?? '',
            'fgts' => $_POST['fgts'] ?? '',
            'observation' => $_POST['observation'] ?? ''


        ];


        if (Client::update($this->pdo, $id, $data)) {
            // Processa uploads
       
            $uploadDir = __DIR__ . '/../../public/uploads/cliente_' . $id;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            // Processa uploads
            if (!empty($_FILES['attachments']['name'][0])) {
                foreach ($_FILES['attachments']['tmp_name'] as $idx => $tmpName) {
                    $name = $_FILES['attachments']['name'][$idx];
                    $dest = $uploadDir . '/' . basename($name);
                    move_uploaded_file($tmpName, $dest);
                }
            }
            $_SESSION['success'] = 'Cliente atualizado com sucesso!';
            header('Location: /clients');
            exit;
        } else {
            $_SESSION['error'] = 'Erro ao atualizar cliente.';
            header('Location: /clients/edit?id=' . $id);
            exit;
        }
    }

    public function delete($id)
    {
        if (!$id) {
            $_SESSION['error'] = 'ID não informado.';
            header('Location: /clients');
            exit;
        }
        if (Client::delete($this->pdo, $id)) {
            $_SESSION['success'] = 'Cliente excluído com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir cliente.';
        }
        header('Location: /clients');
        exit;
    }

    public function generatePdf($id)
    {
        // Garante que o autoload do Dompdf está disponível mesmo sem Composer
        if (!class_exists('\Dompdf\Options')) {
            $dompdfAutoload = __DIR__ . '/../../../vendor/dompdf/autoload.inc.php';
            if (file_exists($dompdfAutoload)) {
                require_once $dompdfAutoload;
            }
        }
        if (!class_exists('\Dompdf\Options')) {
            $_SESSION['error'] = 'Dompdf não encontrado. Verifique a instalação.';
            header('Location: /clients');
            exit;
        }

        if (!$id) {
            $_SESSION['error'] = 'ID não informado.';
            header('Location: /clients');
            exit;
        }
        $client = Client::find($this->pdo, $id);
        if (!$client) {
            $_SESSION['error'] = 'Cliente não encontrado.';
            header('Location: /clients');
            exit;
        }

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        ob_start();
        include __DIR__ . '/../views/clients/pdf_template.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Cria o diretório de uploads para o cliente se não existir
        $uploadDir = __DIR__ . '/../../public/uploads/cliente_' . $id;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Salva o PDF
        $pdfPath = $uploadDir . '/ficha_cadastral.pdf';
        file_put_contents($pdfPath, $dompdf->output());

        // Força o download
        $dompdf->stream("ficha_cadastral_{$id}.pdf", array("Attachment" => true));
        exit;
    }
    
    public function generateClientsListPdf()
    {
        // Garante que o autoload do Dompdf está disponível mesmo sem Composer
        if (!class_exists('\Dompdf\Options')) {
            $dompdfAutoload = __DIR__ . '/../../../vendor/dompdf/autoload.inc.php';
            if (file_exists($dompdfAutoload)) {
                require_once $dompdfAutoload;
            }
        }
        if (!class_exists('\Dompdf\Options')) {
            $_SESSION['error'] = 'Dompdf não encontrado. Verifique a instalação.';
            header('Location: /clients');
            exit;
        }

        $clients = Client::all($this->pdo);

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        ob_start();
        include __DIR__ . '/../views/clients/pdf_list_template.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream("lista_clientes.pdf", array("Attachment" => true));
        exit;
    }
}
