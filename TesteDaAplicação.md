# TESTE DA APLICAÇÃO

## REQUISITOS

Antes de começar, certifique-se de ter instalado:

- [Docker](https://docs.docker.com/get-docker/)  
- [Docker Compose](https://docs.docker.com/compose/install/)

---

## 2. RODANDO OS CONTAINERS

No diretório do projeto (onde está o `docker-compose.yml`), rode:

```bash
docker-compose up -d --build
```
Isso vai subir todos os containers e reconstruir as imagens, se necessário.
---

## 3. ACESSANDO A APLICAÇÃO

- **Front-end:** [http://localhost:3000](http://localhost:3000)  
- **API/PHP:** [http://localhost:8080](http://localhost:8080)  

Espere até que todos os containers iniciem completamente antes de acessar a aplicação.

Certifique-se de que as portas 8080 (API/PHP) e 3000 (Front-end) estão livres no seu computador.


