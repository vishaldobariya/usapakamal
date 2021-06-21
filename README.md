<p align="center">
    <a href="https://industrialax.com/" target="_blank">
        <img src="https://industrialax.com/images/logo.png" height="100px">
    </a>
    <h1 align="center">Admin Basic Project</h1>
    <br>
</p>

##Frontend Assets

For compiling frontend assets project need .babelrc (for suppirting new js features in old browsers) package.json (for linking dependencies) and webpack.config.js.

You free to use any package manager you like.

If you need to add new files into project use ``entry`` block in ``webpack.config.js`` and add there new key with path to your js file.
Styles write with ``scss`` preprocessor and import it in your JS file. Output files will have same name as your key in ``entry`` block and will be placed in ``./web/dist``

In project included boostrap in ``BootstrapAsset`` class. If you need some specific bootstrap modules, see docs [here](https://getbootstrap.com/docs/4.0/getting-started/webpack/)
If you need to rewrite some bootstrap config of styles, use ``_variables.scss`` file. DO NOT rewrite bootstrap styles directly!!

Good luck and have fun)


## ESLINT

To configure ESLint in your PHPStorm IDE see docs [here](https://stackoverflow.com/questions/46641682/how-to-configure-eslint-auto-fix-on-save-in-webstorm-phpstorm).

## STYLELINT

To configure stylelint in project go to IDE preferences -> 
File Watchers and create new watcher for scss files.

* Scope ``Project Files``
* Program ``$ProjectFileDir$/node_modules/.bin/stylelint``
* Arguments ``--syntax scss --fix $FilePath$``
* Output path to refresh ``$FileDir$``
