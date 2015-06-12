# Joomla 3.x frontend component for oneContent

This repository contains the contents of `<JPATH_SITE>/components/com_one`. 

## Development install

Assuming you have a repository for your site, use these commands:

```
  $ cd <JPATH_SITE>
  $ git submodule add https://github.com/pdelbar/one-content-site-component.git components/com_one  
  $ git submodule init
  $ git submodule update
  $ git checkout master
```

Make sure to commit the .gitmodules file. [Working with modules is explained here](https://chrisjean.com/2009/04/20/git-submodules-adding-using-removing-and-updating/).
