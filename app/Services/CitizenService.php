<?php


// app/Services/CitizenService.php
namespace App\Services;

use App\Repositories\CitizenRepository;

class CitizenService
{
    protected $citizenRepository;
    public function __construct(CitizenRepository $citizenRepository)
    {
        $this->citizenRepository = $citizenRepository;
    }
    public function getAll()
    {
        return $this->citizenRepository->all();
    }
    public function getById($id)
    {
        return $this->citizenRepository->find($id);
    }
    public function getByZaloId($zaloId)
    {
        return $this->citizenRepository->findByZaloId($zaloId);
    }
    public function create(array $data)
    {
        return $this->citizenRepository->create($data);
    }
    public function update($id, array $data)
    {
        return $this->citizenRepository->update($id, $data);
    }
    public function delete($id)
    {
        return $this->citizenRepository->delete($id);
    }
}
