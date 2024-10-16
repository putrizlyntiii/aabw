<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelAkun3 extends Model
{
    protected $table            = 'akun3s';
    protected $primaryKey       = 'id_akun3';
    protected $returnType       = 'object';
    protected $allowedFields    = ['kode_akun3', 'nama_akun3', 'kode_akun2', 'kode_akun1'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'kode_akun3' => 'string',
        'nama_akun3' => 'string',
        // add other casts as needed
    ];

    // Dates
    protected $useTimestamps = true; // Enable timestamps
    protected $createdField  = 'created_at'; // Field for created timestamp
    protected $updatedField  = 'updated_at'; // Field for updated timestamp

    // Validation
    protected $validationRules      = [
        'kode_akun3' => 'required|min_length[3]|max_length[50]',
        'nama_akun3' => 'required|min_length[3]|max_length[100]',
        // Add rules for other fields as needed
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    function ambilrelasi()
    {
        $builder = $this->db->table('akun3s');
        $builder->join('akun1s', 'akun1s.kode_akun1 = akun3s.kode_akun1');
        $builder->join('akun2s', 'akun2s.kode_akun2 = akun3s.kode_akun2');
        $query = $builder->get();
        return $query->getResult();
    }
}
