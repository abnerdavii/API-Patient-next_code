# API Patient - Laravel

Este projeto é uma **API RESTful** desenvolvida em **Laravel**, criada como parte de um desafio técnico para a vaga na **Next_Code**.  
O objetivo é implementar um **CRUD completo** para a entidade **Patient**, com suporte a **validação de dados**, **tratamento de erros** e **paginação** de resultados.  

---

## Tecnologias utilizadas
- [Laravel](https://laravel.com/) (Framework PHP)
- PHP 8+
- Composer
- Banco de dados MySQL

---

## ⚙️ Como rodar o projeto

1. Clone o repositório:
   ```bash
   git clone https://github.com/abnerdavii/API-Patient-next_code.git
   cd API-Patient-next_code
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   php artisan serve

A API estará disponível em:
👉 http://127.0.0.1:8000


## 📌 Entidade Patient

Campos disponíveis na entidade patients:
<pre>
id (int, PK, auto-increment)
name (string, obrigatório)
date_of_birth (date, obrigatório)
address (string, obrigatório)
phone (string, opcional)
medical_history (text, opcional)
</pre>

| Método | Rota                 | Descrição                                |
| ------ | -------------------- | ---------------------------------------- |
| GET    | `/api/patients`      | Lista todos os pacientes (com paginação) |
| GET    | `/api/patients/{id}` | Retorna um paciente específico           |
| POST   | `/api/patients`      | Cria um novo paciente                    |
| PUT    | `/api/patients/{id}` | Atualiza um paciente existente           |
| DELETE | `/api/patients/{id}` | Remove um paciente                       |

## 🔎 Exemplos de requisição
Criar paciente
POST /api/patients
Content-Type: application/json
``` 
{
  "name": "João da Silva",
  "date_of_birth": "1990-05-12",
  "address": "Rua das Flores, 123",
  "phone": "11999999999",
  "medical_history": "Paciente com histórico de asma"
}
```

Resposta de sucesso:
```
{
  "id": 1,
  "name": "João da Silva",
  "date_of_birth": "1990-05-12",
  "address": "Rua das Flores, 123",
  "phone": "11999999999",
  "medical_history": "Paciente com histórico de asma",
  "created_at": "2025-08-26T15:00:00.000000Z",
  "updated_at": "2025-08-26T15:00:00.000000Z"
}
```


