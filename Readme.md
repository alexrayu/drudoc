# DruDoc
The Drupal Documentation Creator.

Two files are created - `output.md` and `output.html`.

## Installing DruDoc
DruDoc comes as is, and does not require additional installation.

To make DruDoc available like an app, run the commands:
```bash
cp drudoc.phar /usr/local/bin/drudoc
chmod +x /usr/local/bin/drudoc
```

## Running DruDoc
`php drudoc.phar create path_to_module path_to_output_folder`
The output folder must exist. `.` Can be used to create the output in the same 
folder as the phar file.
