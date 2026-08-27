const fs = require("fs");
const path = require("path");

const ROMS_DIR = path.join(process.cwd(), "roms");
const OUTPUT = path.join(process.cwd(), "roms.json");

function walk(dir) {
    if (!fs.existsSync(dir)) {
        return [];
    }

    const result = [];

    for (const entry of fs.readdirSync(dir, {
        withFileTypes: true
    })) {

        const fullPath =
            path.join(dir, entry.name);

        if (entry.isDirectory()) {

            result.push(
                ...walk(fullPath)
            );

        } else if (entry.isFile()) {

            const relativePath =
                path.relative(
                    process.cwd(),
                    fullPath
                )
                .split(path.sep)
                .join("/");

            result.push(relativePath);
        }
    }

    return result.sort(
        (a, b) =>
            a.localeCompare(b)
    );
}

const roms =
    walk(ROMS_DIR);

fs.writeFileSync(
    OUTPUT,
    JSON.stringify(
        roms,
        null,
        2
    ) + "\n",
    "utf8"
);

console.log(
    `Generated roms.json with ${roms.length} file(s).`
);