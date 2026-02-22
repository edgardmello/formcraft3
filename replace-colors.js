const fs = require('fs');
const path = require('path');

const srcDir = path.join(__dirname, 'src', 'less');

const colorMap = [
    { regex: /#ffffff\b|#fff\b/gi, replacement: 'var(--fc-color-surface)' },
    { regex: /#e8e8e8\b|#e7e8e9\b|#eeeeee\b|#eee\b|#cccccc\b|#ccc\b/gi, replacement: 'var(--fc-color-border)' },
    { regex: /#111111\b|#111\b|#222222\b|#222\b|#333333\b|#333\b|#444444\b|#444\b|#555555\b|#555\b/gi, replacement: 'var(--fc-color-text)' },
    { regex: /#666666\b|#666\b|#777777\b|#777\b|#888888\b|#888\b|#999999\b|#999\b/gi, replacement: 'var(--fc-color-text-muted)' },
    { regex: /#e86464\b|#f05050\b|#f56e6e\b/gi, replacement: 'var(--fc-color-error)' },
    { regex: /#47b181\b|#48b182\b/gi, replacement: 'var(--fc-color-success)' },
    { regex: /#4488ee\b|#48e\b|#6495ed\b/gi, replacement: 'var(--fc-color-primary)' },
];

const files = ['form.less', 'formcraft-builder.less', 'common-elements.less'];

for (const file of files) {
    const filePath = path.join(srcDir, file);
    if (!fs.existsSync(filePath)) continue;

    let content = fs.readFileSync(filePath, 'utf8');

    for (const rule of colorMap) {
        content = content.replace(rule.regex, rule.replacement);
    }

    // Replace explicit string "white" outside quotes or properties but keep carefully.
    content = content.replace(/:\s*white\b/g, ': var(--fc-color-surface)');
    content = content.replace(/:\s*transparent\b/g, ': transparent');

    fs.writeFileSync(filePath, content);
    console.log(`Replaced colors in ${file}`);
}
