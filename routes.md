
# Rotas


### **Login e Registro**

**Post:** /login
```bash
    {
        "email": "email@teste.com",
        "password": "senha123"
    }
```
**Post:** /register
```bash
    {
        "name": "Jhon Doe",
        "email": "email@teste.com",
        "password": "senha123"
    }
```

## **User**
**POST:** /users/{id}/update-image
```bash
    name = image_path
    value = file
```
**GET:** /users/{id}

**PUT:** /users/{id}

```bash
// Somente o dono do perfil pode atualizar.

    {
        "name": "Jhon Doe 2",
        "email": "email@updated.com",
    }
```
**DELETE:** /users/{id}


