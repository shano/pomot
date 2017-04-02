pomot is a cli client that can be used to interact with [pomotodo.com](https://pomotodo.com)

# Features

* Can list/create todos.
* Can list/create pomodoros.
* Can run anywhere PHP 7+ can

# Installation

* Git clone this repo using `git clone git@github.com:shano/pomot.git`
* Get your pomotodo authorisation key [here](https://pomotodo.com/developer)
* Place your key the config file at `src/Resources/configuration/configuration.yml` with contents:

```
# src/Resources/config/configuration.yml
auth_key: auth_key_goes_here
```

# Usage

* List todos - `php app todo:list`
* Create a todo - `php app todo:create 'Todo description with #hash #tags'`
* List pomodoros - `php app pomo:list`
* Create a pomodoro timer(creates pomodoro on completion) - `php app pomo:create 'Pomo description with #hash $tags'`

# Todos

* Add todo:complete command
* Add todo:delete command
* Easier way to create pomodoros from todos
* Ability to play sounds based on events
* Package application as a self contained phar for distribution.
* Tidy up tests
