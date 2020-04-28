# download-files-stream-control
Download file from server files by controlling number bytes send by request.

How to test:

Open terminal and type:

cd server

php -S localhost:8000

Open new terminal and type:

cd wormhole

php wormhole.php

To edit the config default open wormhole/config.json

- url: URL to acess the server files.
- kbps: Qtd kb/s to download the files.
- interval: Interval to download a new part.
- extTempFile: Name of the extension of files cache. Eg: part-001.afe
- suffixTempFile: Suffix to name of files cache. Eg part-001.afe
- forceDownload: Tells if must download again when script runs.

The list of the files who must be downloaded can be found in: wormhole/files-download-list.json

Those files must be in folder server/imgs.
