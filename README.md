## Инструкция

### Склонировать проект

```
git clone ... test-symfony-api 
```
###  Перейти в папку проекта
```  
cd test-symfony-api
```
###  Установить зависимости
```
make composer COMMAND="install --no-suggest --prefer-dist"
```
###  Запустить php сервер
```
make run-server  
```
^ запустится встроенный php сервер на порту 8000

###  Запустить юнит тесты
```
make run-unit-tests
```
###  Запустить апи тесты
```
make run-api-tests
```
