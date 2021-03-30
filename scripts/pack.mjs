import { createWriteStream, promises as fs  } from 'fs';
import path from 'path';
import archiver from 'archiver';
import { cwd } from 'process';

const OUTPUT_FILENAME = `get-id-verified.zip`;
const INCLUDE = [
  `admin/`,
  `includes/`,
  `languages/`,
  `public/`,
  `get-id-verified.php`,
  `index.php`,
  `LICENSE.txt`,
  `README.md`,
  `uninstall.php`,
];

try {
  await fs.access(OUTPUT_FILENAME);
  await fs.unlink(OUTPUT_FILENAME);
} catch (e) { }

const output = createWriteStream(path.join(cwd(), `/${OUTPUT_FILENAME}`));
const archive = archiver('zip', { zlib: { level: 9 } });

archive.on('error', function(err) {
  throw err;
});

archive.pipe(output);
INCLUDE.forEach((file) => {
  if (file.endsWith('/')) {
    archive.directory(file, true);
  } else {
    archive.file(file, { name: file });
  }
});
archive.finalize();