const fs = require('fs');
const path = require('path');
const less = require('less');
const CleanCSS = require('clean-css');

const srcDir = path.join(__dirname, 'src', 'less');
const distDir = path.join(__dirname, 'dist');

if (!fs.existsSync(distDir)){
    fs.mkdirSync(distDir, { recursive: true });
}

const lessFiles = fs.readdirSync(srcDir).filter(f => f.endsWith('.less') && f !== 'less-variables.less' && !f.startsWith('_'));
const cssFiles = fs.readdirSync(srcDir).filter(f => f.endsWith('.css'));

async function buildAll() {
    for (const file of lessFiles) {
        const srcPath = path.join(srcDir, file);
        const distFile = file.replace('.less', '.css');
        const distPath = path.join(distDir, distFile);

        try {
            const lessFileContent = fs.readFileSync(srcPath, 'utf8');
            const output = await less.render(lessFileContent, {
                filename: srcPath,
                paths: [srcDir]
            });

            const minified = new CleanCSS({}).minify(output.css);
            fs.writeFileSync(distPath, minified.styles);
            console.log(`Compiled and minified: ${file} -> ${distFile}`);
        } catch (e) {
            console.error(`Error compiling ${file}:`, e.message);
        }
    }

    for (const file of cssFiles) {
        const srcPath = path.join(srcDir, file);
        const distPath = path.join(distDir, file);
        try {
            const content = fs.readFileSync(srcPath, 'utf8');
            const minified = new CleanCSS({}).minify(content);
            fs.writeFileSync(distPath, minified.styles);
            console.log(`Copied and minified pure CSS: ${file} -> ${file}`);
        } catch (e) {
            console.error(`Error processing ${file}:`, e.message);
        }
    }
}

buildAll();
