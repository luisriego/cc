# GIT 

###Iniciar un servicio de git em el archivo local
    git init

### Ver el status actual
    git status


### Retornar al último commit
Fuerza al directorio de trabajo a volver al último commit y borra los archivo nuevos o modificados.

    git reset --hard
    git clean


#### La forma más profesional
    git branch changes
    git checkout changes
    git add .
    git commit
    
    git checkout master
    
### Borrar un 'remote'
    git remote rm origin