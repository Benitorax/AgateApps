/*
 * There here is YOUR config
 * This var is an example of the assets you can use in your own application.
 * The array KEYS correspond to the OUTPUT files/directories,
 * The array VALUES contain a LIST OF SOURCE FILES
 */
const config = {

    // The base output directory for all your assets
    "output_directory": "public",

    /**
     * This option is used to simply copy files into a new directory.
     * This is very useful when copying dist files of external components.
     * Example:
     *     "fonts/": [
     *         "node_modules/materialize-css/dist/fonts/*",
     *         "node_modules/bootstrap/dist/fonts/*"
     *     ]
     */
    "copy": {
        "fonts/": [
            "node_modules/font-awesome/fonts/*"
        ],
        "images/": [
            "node_modules/leaflet/dist/images/*"
        ],
        // Painful, but if we don't want to modify leaflet-draw, must be done.
        "css/images/": [
            "node_modules/leaflet-draw/dist/images/*"
        ]
    },

    /**
     * Here you can add other files to watch when using "gulp watch".
     * They will automatically run the "dump" command when modified.
     * It is VERY useful when you use massively less/sass "import" rules, for example.
     * Example:
     *     "src/AppBundle/Resources/public/less/*.less"
     */
    "files_to_watch": [
        "assets/esteren/less/*.less",
        "assets/esteren/css/*.css",
        "assets/esteren/sass/*.scss",
        "assets/esteren/sass/theme_components/*.scss",
        "assets/corahn_rin/generator/css/*.scss"
    ],

    /**
     * All files here are images that will be optimized/compressed with imagemin.
     * The key corresponds to the output directory (prepended with "output_directory" previous option).
     * Example:
     *     "images/": [
     *         "src/AppBundle/Resources/public/images/*"
     *     ]
     */
    "images": {
        "img/markerstypes": [
            "assets/esteren_maps/img/markerstypes/*"
        ],
        "img/generator": [
            "assets/corahn_rin/img/*"
        ],
        "img/agate": [
            "assets/agate/images/**"
        ],
        "img/dragons": [
            "assets/dragons/images/**"
        ],
        "img/": [
            "assets/esteren/images/**"
        ]
    },

    /**
     * All files from this section are parsed with LESS plugin and dumped into a CSS file.
     * If using "--prod" in command line, will minify with "clean-css".
     * Example:
     *     "css/main_less.css": [
     *         "node_modules/bootstrap/less/bootstrap.less",
     *         "src/AppBundle/Resources/public/less/main.less"
     *     ]
     */
    "less": {
        "css/fa.css": [
            "node_modules/font-awesome/less/font-awesome.less"
        ],
        "css/maps_lib.css": [
            "assets/esteren_maps/less/maps.less"
        ],
        "css/admin.css": [
            "assets/admin.less"
        ],
        "css/character_details.css": [
            "assets/corahn_rin/generator/css/character_details.scss"
        ]
    },

    /**
     * All files from this section are parsed with SASS plugin and dumped into a CSS file.
     * If using "--prod" in command line, will minify with "clean-css".
     * Example:
     *     "css/main_sass.css": [
     *         "src/AppBundle/Resources/public/less/main.scss"
     *     ]
     */
    "sass": {
        "css/global.css": [
            "assets/esteren/sass/main.scss"
        ],
        "css/agate.css": [
            "assets/agate/sass/agate-theme.scss"
        ],
        "css/dragons.css": [
            "assets/dragons/sass/dragons-theme.scss"
        ],
        "css/generator.css": [
            "assets/corahn_rin/generator/css/main_steps.scss"
        ],
        "css/white_layout.css": [
            "assets/esteren/sass/white_layout.scss"
        ]
    },

    /**
     * All files from this section are just concatenated and dumped into a CSS file.
     * If using "--prod" in command line, will minify with "clean-css".
     * Example:
     *     "css/main.css": [
     *         "src/AppBundle/Resources/public/css/main.css"
     *     ]
     */
    "css": {
        "css/initializer.css": [
            "assets/esteren/css/initializer.css"
        ],
        "css/feond-beer.css": [
            "assets/esteren/css/feond-beer.css"
        ]
    },

    /**
     * All files from this section are just concatenated and dumped into a JS file.
     * If using "--prod" in command line, will minify with "uglifyjs".
     * Example:
     *     "js/main.js": [
     *         "web/components/bootstrap/dist/bootstrap-src.js",
     *         "src/AppBundle/Resources/public/css/main.js"
     *     ]
     */
    "js": {
        "js/jquery.js": [
            "node_modules/jquery/dist/jquery.js"
        ],
        "js/global.js": [
            "node_modules/materialize-css/dist/js/materialize.js",
            "assets/agate/js/connect.js",
            "assets/esteren/js/helpers.js",
            "assets/esteren/js/global.js"
        ],
        "js/maps_lib.js": [
            "node_modules/leaflet/dist/leaflet-src.js",
            "public/components/leaflet-sidebar/src/L.Control.Sidebar.js",
            "node_modules/leaflet-draw/dist/leaflet.draw-src.js",
            "assets/esteren_maps/js/EsterenMap/1_EsterenMap.js",
            "assets/esteren_maps/js/EsterenMap/1_EsterenMap.load.js",
            "assets/esteren_maps/js/EsterenMap/2_EsterenMap_CRS_XY.js",
            "assets/esteren_maps/js/EsterenMap/2_EsterenMap_directions.js",
            "assets/esteren_maps/js/EsterenMap/2_EsterenMap_LatLngBounds.js",
            "assets/esteren_maps/js/EsterenMap/2_EsterenMap_Sidebar.js",
            "assets/esteren_maps/js/EsterenMap/3_EsterenMap_ActivateLeafletDraw.js",
            "assets/esteren_maps/js/EsterenMap/3_EsterenMap_options.js",
            "assets/esteren_maps/js/EsterenMap/4_EsterenMap_markers.js",
            "assets/esteren_maps/js/EsterenMap/4_EsterenMap_polygons.js",
            "assets/esteren_maps/js/EsterenMap/4_EsterenMap_polylines.js",
            "assets/esteren_maps/js/EsterenMap/4_EsterenMap_filters.js",
            "assets/esteren_maps/js/EsterenMap/4_EsterenMap_search_engine.js",
            "assets/esteren_maps/js/EsterenMap/5_EsterenMap_mapEdit.js"
        ],
        "js/generator.js": [
            "assets/corahn_rin/generator/js/main_steps.js"
        ],
        "js/step_03_birthplace.js": ["assets/corahn_rin/generator/js/step_03_birthplace.js"],
        "js/step_11_advantages.js": [
            "assets/corahn_rin/generator/js/step_11_advantage_class.js",
            "assets/corahn_rin/generator/js/step_11_functions.js",
            "assets/corahn_rin/generator/js/step_11_advantages_process.js"
        ],
        "js/step_13_primary_domains.js": ["assets/corahn_rin/generator/js/step_13_primary_domains.js"],
        "js/step_14_use_domain_bonuses.js": ["assets/corahn_rin/generator/js/step_14_use_domain_bonuses.js"],
        "js/step_15_domains_spend_exp.js": ["assets/corahn_rin/generator/js/step_15_domains_spend_exp.js"],
        "js/step_16_disciplines.js": ["assets/corahn_rin/generator/js/step_16_disciplines.js"],
        "js/step_17_combat_arts.js": ["assets/corahn_rin/generator/js/step_17_combat_arts.js"],
        "js/step_18_equipment.js": ["assets/corahn_rin/generator/js/step_18_equipment.js"]
    }
};

/*************** End config ***************/

// Everything AFTER this line of code is updatable to the latest version of this gulpfile.
// Check it out there if you need: https://github.com/Orbitale/Gulpfile

/************* Some helpers *************/

var GulpfileHelpers = {};

/**
 * @param {Object} object
 * @returns {Number}
 */
GulpfileHelpers.objectSize = function(object) {
    "use strict";
    let size = 0;

    for (let key in object) {
        if (object.hasOwnProperty(key)) {
            size++;
        }
    }

    return size;
};

/**
 * @param {Object} object
 * @param {Function} callback
 * @returns {Object}
 */
GulpfileHelpers.objectForEach = function(object, callback) {
    "use strict";
    for (let key in object) {
        if (object.hasOwnProperty(key)) {
            callback.apply(object, [key, object[key]]);
        }
    }

    return object;
};

/**
 * @param {Array} arrayToFlatten
 * @returns {Array}
 */
GulpfileHelpers.flattenArray = function(arrayToFlatten) {
    "use strict";
    return [].concat.apply([], arrayToFlatten).sort();
};

/**
 * Retrieve files from globs or specific file names.
 */
GulpfileHelpers.pushFromDirectory = (outputDirName, elements, filesList) => {
    if (!filesList) {
        filesList = [];
    }

    let finalOutput = config.output_directory+'/'+outputDirName;

    let cleanName = (outputDirectory, sourceName) => {
        let cleanName = outputDirectory.replace(/\/\*+/gi, '');
        // Clean up files and dirs so we can convert a "source" name into an "output dir related" name.
        let replacedFileName = sourceName.replace(cleanName, '');
        return (finalOutput+replacedFileName).replace('//', '/');
    };

    let cleanAndPush = (name, files) => {
        files.forEach((sourceName) => {
            let cleaned = cleanName(name, sourceName);
            filesList.push(cleaned);
        });
    };

    elements.forEach((name) => {
        // Name is something like "node_modules/materialize-css/dist/fonts/*".
        // We'll check for globs and direct files here to add them to files to watch for tests.
        // This is more complex as we have to list all files in source dirs and compare them with output dir.
        if (glob.hasMagic(name)) {
            cleanAndPush(name, glob.sync(name, { nodir: true }));
        } else {
            let stat = fs.statSync(name);
            if (stat.isFile()) {
                filesList.push(name);
            } else if (stat.isDirectory()) {
                // Force glob search for directories
                cleanAndPush(name, glob.sync(name.replace(/\/+$/gi, '')+'/**', { nodir: true }));
            } else {
                throw 'Could not find a way to handle source "'+name+'".';
            }
        }
    });
};

/*************** Global vars ***************/

// These data are mostly used to introduce logic that will save memory and time.
const isProd    = process.argv.indexOf('--prod') >= 0;
const hasImages = GulpfileHelpers.objectSize(config.images) > 0;
const hasCopy   = GulpfileHelpers.objectSize(config.copy) > 0;
const hasLess   = GulpfileHelpers.objectSize(config.less) > 0;
const hasSass   = GulpfileHelpers.objectSize(config.sass) > 0;
const hasCss    = GulpfileHelpers.objectSize(config.css) > 0;
const hasJs     = GulpfileHelpers.objectSize(config.js) > 0;

// Required extensions
const fs         = require('fs');
const path       = require('path');
const pump       = require('pump');
const glob       = require('glob');
const gulp       = require('gulp4');
const gulpif     = require('gulp-if');
const concat     = require('gulp-concat');
const uglify     = require('gulp-uglify');
const cleancss   = require('gulp-clean-css');
const gulpWatch  = require('gulp-watch');

// Load other extensions only when having specific components. Saves memory & time execution.
const less     = hasLess   ? require('gulp-less')     : function(){ return {}; };
const sass     = hasSass   ? require('gulp-sass')     : function(){ return {}; };
const imagemin = hasImages ? require('gulp-imagemin') : function(){ return {}; };

/************** Files checks **************/

var erroredFiles = [];

var checkCallback = function(key, values) {
    values.forEach(function(fileName) {
        try {
            // Remove wildcards
            fileName = fileName.replace(/(?:(?:\*\.\w{2,4}(?:$|\/))|(?:\/\*+(?:$|\/)))/gi, '');
            fs.statSync(fileName);
        } catch (e) {
            if (e.code === 'ENOENT' || (e.message && e.message.match(/no such file/i)) || String(e).match(/no such file/i)) {
                erroredFiles.push(fileName);
            } else {
                throw e;
            }
        }
    })
};

GulpfileHelpers.objectForEach(config.css, checkCallback);
GulpfileHelpers.objectForEach(config.js, checkCallback);
GulpfileHelpers.objectForEach(config.images, checkCallback);
GulpfileHelpers.objectForEach(config.copy, checkCallback);
GulpfileHelpers.objectForEach(config.sass, checkCallback);
GulpfileHelpers.objectForEach(config.less, checkCallback);

if (erroredFiles.length) {
    throw "Missing input files: \n"+erroredFiles.join("\n")+"\n";
}

/*************** Gulp tasks ***************/

/**
 * Dumps the LESS assets
 */
gulp.task('less', function(done) {
    "use strict";

    let list = config.less,
        outputDir = config.output_directory+'/',
        assets_output, assets, i, l,
        outputCount = GulpfileHelpers.objectSize(list),
        pipesDone = 0
    ;

    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pump([
            gulp.src(assets),
            less(),
            concat(assets_output),
            gulpif(isProd, cleancss()),
            concat(assets_output),
            gulp.dest(outputDir),
        ], finalCallback);

        console.info(" [file+] "+assets_output);
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    function finalCallback() {
        pipesDone++;
        if (outputCount === pipesDone) {
            done();
        }
    }
});

/**
 * Dumps the SASS assets
 */
gulp.task('sass', function(done) {
    "use strict";

    let list = config.sass,
        outputDir = config.output_directory+'/',
        assets_output, assets, i, l,
        outputCount = GulpfileHelpers.objectSize(list),
        pipesDone = 0
    ;

    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pump([
            gulp.src(assets),
            sass().on('error', sass.logError),
            concat(assets_output),
            gulpif(isProd, cleancss()),
            concat(assets_output),
            gulp.dest(outputDir)
        ], finalCallback);

        console.info(" [file+] "+assets_output);
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    function finalCallback() {
        pipesDone++;
        if (outputCount === pipesDone) {
            done();
        }
    }
});

/**
 * Simply copy files into another directory.
 * Useful for simple "dist" files from node_modules directory, for example.
 */
gulp.task('copy', function(done) {
    "use strict";

    let list = config.copy,
        outputDir = config.output_directory+'/',
        assets_output, assets, i, l,
        outputCount = GulpfileHelpers.objectSize(list),
        pipesDone = 0
    ;

    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pump([
            gulp.src(assets),
            gulp.dest(outputDir + assets_output)
        ], finalCallback);

        console.info(" [file+] "+assets_output);
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    function finalCallback() {
        pipesDone++;
        if (outputCount === pipesDone) {
            done();
        }
    }
});

/**
 * Compress images.
 * Thanks to @docteurklein.
 */
gulp.task('images', function(done) {
    "use strict";

    let list = config.images,
        outputDir = config.output_directory+'/',
        assets_output, assets, i, l,
        outputCount = GulpfileHelpers.objectSize(list),
        pipesDone = 0
    ;

    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pump([
            gulp.src(assets),
            imagemin([
                imagemin.gifsicle(isProd ? {interlaced: true} : {}),
                imagemin.jpegtran(isProd ? {progressive: true} : {}),
                imagemin.optipng(isProd ? {optimizationLevel: 7} : {}),
                imagemin.svgo({plugins: [{removeViewBox: true}]})
            ]),
            gulp.dest(outputDir + assets_output),
        ], finalCallback);

        console.info(" [file+] "+assets_output);
        for (i = 0, l = assets.length; i < l; i++) {
            console.info('       > '+assets[i]);
        }
    }

    function finalCallback() {
        pipesDone++;
        if (outputCount === pipesDone) {
            done();
        }
    }
});

/**
 * Dumps the CSS assets.
 */
gulp.task('css', function(done) {
    "use strict";

    let list = config.css,
        outputDir = config.output_directory+'/',
        assets_output, assets, i, l,
        outputCount = GulpfileHelpers.objectSize(list),
        pipesDone = 0
    ;
    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pump([
            gulp.src(assets),
            concat(assets_output),
            gulpif(isProd, cleancss()),
            concat(assets_output),
            gulp.dest(outputDir)
        ], finalCallback);

        console.info(" [file+] "+assets_output);
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    function finalCallback() {
        pipesDone++;
        if (outputCount === pipesDone) {
            done();
        }
    }
});

/**
 * Dumps the JS assets
 */
gulp.task('js', function(done) {
    "use strict";

    let list = config.js,
        outputDir = config.output_directory+'/',
        assets_output, assets, i, l,
        outputCount = GulpfileHelpers.objectSize(list),
        pipesDone = 0
    ;

    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pump([
            gulp.src(assets),
            concat({path: assets_output, cwd: ''}),
            gulpif(isProd, uglify()),
            gulp.dest(outputDir)
        ], finalCallback);

        console.info(" [file+] "+assets_output);
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    function finalCallback() {
        pipesDone++;
        if (outputCount === pipesDone) {
            done();
        }
    }
});

/**
 * Runs all the needed commands to dump all assets and manifests
 */
gulp.task('dump', gulp.series('images', 'copy', 'less', 'sass', 'css', 'js', function(done){
    done();
}));

/**
 * Will watch for files and run "dump" for each modification
 */
gulp.task('watch', gulp.series(/*'dump', */gulp.parallel(function(done) {
    "use strict";

    let files_less = [],
        files_images = [],
        files_copy = [],
        files_css = [],
        files_sass = [],
        files_js = [],
        files_to_watch = [],
        other_files_to_watch = config.files_to_watch || [],
        forEach = GulpfileHelpers.objectForEach,
        push = (key, elements, appendTo) => {
            appendTo.push(elements);
            files_to_watch.push(elements);
        },
        callback = function(event) {
            console.log('File "' + event + '" updated');

            let envFile = __dirname + path.sep + '.env';
            fs.exists(envFile, function(exists) {
                if (!exists) {
                    console.log('No env file.');
                    done();
                    return;
                }

                console.log('Opening env file ' + envFile);
                fs.readFile(envFile, 'utf8', function(err, fileData){
                    if (err) {
                        return done();
                    }

                    if (!fileData.match('RELEASE_VERSION')) {
                        fileData += "\nRELEASE_VERSION=\"v0\"\n";
                    }

                    fileData = fileData.replace(/RELEASE_VERSION=("?v?)(\d+)("?)/g, function(match, p1, versionNumber, p2) {
                        console.log('Current version is ' + versionNumber + '. Upgrading to ' + (++versionNumber));

                        return 'RELEASE_VERSION=' + p1 + versionNumber + p2;
                    });

                    fs.writeFile(envFile, fileData, done);
                });
            });
        }
    ;

    console.info('Night gathers, and now my watch begins...');

    forEach(config.images, (key, images) => { push(key, images, files_images); });
    forEach(config.copy, (key, images) => { push(key, images, files_copy); });
    forEach(config.less, (key, images) => { push(key, images, files_less); });
    forEach(config.sass, (key, images) => { push(key, images, files_sass); });
    forEach(config.css, (key, images) => { push(key, images, files_css); });
    forEach(config.js, (key, images) => { push(key, images, files_js); });

    if (files_to_watch.length) {
        console.info('Watching file(s):');
        // Flatten the array
        files_to_watch = [].concat.apply([], files_to_watch).sort();
        console.info("       > "+files_to_watch.join("\n       > "));
    }

    // On Windows, filesystem events are not supported.
    // This is why we use polling on windows to make the watch script work properly.
    let watchOptions = {
        usePolling: true
    };

    if (other_files_to_watch.length) {
        gulpWatch(other_files_to_watch, watchOptions, gulp.parallel('dump')).on('change', callback);
    }
    if (hasImages) {
        gulpWatch(GulpfileHelpers.flattenArray(files_images), watchOptions, gulp.parallel('images')).on('change', callback);
    }
    if (hasCopy) {
        gulpWatch(GulpfileHelpers.flattenArray(files_copy), watchOptions, gulp.parallel('copy')).on('change', callback);
    }
    if (hasLess) {
        gulpWatch(GulpfileHelpers.flattenArray(files_less), watchOptions, gulp.parallel('less')).on('change', callback);
    }
    if (hasSass) {
        gulpWatch(GulpfileHelpers.flattenArray(files_sass), watchOptions, gulp.parallel('sass')).on('change', callback);
    }
    if (hasCss) {
        gulpWatch(GulpfileHelpers.flattenArray(files_css), watchOptions, gulp.parallel('css')).on('change', callback);
    }
    if (hasJs) {
        gulpWatch(GulpfileHelpers.flattenArray(files_js), watchOptions, gulp.parallel('js')).on('change', callback);
    }

    done();
})));

gulp.task('clean', function(done) {
    "use strict";
    let filesList = [];
    let forEach   = GulpfileHelpers.objectForEach;
    let push      = (key) => {
        filesList.push(config.output_directory + '/' + key);
    };
    let pushFromDir = (outputDirName, elements) => {
        GulpfileHelpers.pushFromDirectory(outputDirName, elements, filesList);
    };

    /**
     * Retrieve files list.
     */
    forEach(config.less, push);
    forEach(config.sass, push);
    forEach(config.css, push);
    forEach(config.js, push);
    forEach(config.images, pushFromDir);
    forEach(config.copy, pushFromDir);

    console.info('Clean existing files.');

    let number = filesList.length;
    let processedFiles = 0;
    let invalid = [];

    filesList.forEach(function(file){
        let fullPath = path.resolve(__dirname.replace(/\/$/, '')+'/'+file.replace(/^\/?/g, ''));
        fs.unlink(fullPath, function(err){
            processedFiles++;

            if (err) {
                if (err.code !== 'ENOENT') {
                    invalid.push(fullPath+" ("+err+")");
                }
            } else {
                process.stdout.write(' Removing '+file+"\n");
            }

            if (processedFiles === number) {
                if (!invalid.length) {
                    process.stdout.write("No files!\n");
                }
                finish();
            }
        });
    });

    function finish() {
        if (invalid.length) {
            process.stdout.write("These files seem not to have been correctly removed by Gulp flow:\n");
            invalid.forEach((file) => {
                process.stdout.write(" > "+file+"\n");
            });
        }

        done();
    }
});

gulp.task('test', gulp.series('clean', 'dump', function(done) {
    "use strict";
    let filesList = [];
    let forEach = GulpfileHelpers.objectForEach;
    let push = (key) => {
        filesList.push(config.output_directory+'/'+key);
    };
    let pushFromDir = (outputDirName, elements) => {
        GulpfileHelpers.pushFromDirectory(outputDirName, elements, filesList);
    };

    /**
     * Retrieve files list.
     */
    forEach(config.less, push);
    forEach(config.sass, push);
    forEach(config.css, push);
    forEach(config.js, push);
    forEach(config.images, pushFromDir);
    forEach(config.copy, pushFromDir);

    console.info('Check files that are not dumped according to config.');

    let number = filesList.length;
    let processedFiles = 0;
    let valid = 0;
    let invalid = [];

    // Hack for "padding" strings
    let numberLength = String(number).length;
    let padString = '';
    for (let i = 0; i < numberLength; i++) {
        padString += ' ';
    }

    // Manual "left-pad"
    let pad = (s, p) => { if (typeof p === 'undefined') { p = padString; } return String(p+s).slice(-p.length); };

    filesList.forEach(function(file){
        let fullPath = path.resolve(__dirname.replace(/\/$/, '')+'/'+file.replace(/^\/?/g, ''));
        fs.access(fullPath, function(err){
            if (!err) {
                valid++;
                process.stdout.write('.');
            } else {
                invalid.push(file);
                process.stdout.write('F');
            }

            processedFiles++;

            if (processedFiles % 50 === 0 && processedFiles !== number) {
                process.stdout.write(' '+pad(processedFiles)+' / ' + number + ' (' + pad((Math.floor(100 * processedFiles / number)), '   ') + "%)\n");
            }

            if (processedFiles === number) {
                let rest = 50 - (processedFiles % 50);
                let spaces = '';
                for (let i = 0; i < rest; i++) {
                    spaces += ' ';
                }
                process.stdout.write(' '+spaces+valid+' / ' + processedFiles + " (100%)\n");

                finish();
            }
        });
    });

    function finish() {
        if (invalid.length) {
            let msg = '';
            msg += "These files seem not to have been dumped by Gulp flow:\n";
            invalid.forEach((file) => {
                console.info(file);
                msg += " > "+file.toString()+"\n";
            });

            throw new Error(msg);
        }

        done();
    }
}));

/**
 * Small user guide
 */
gulp.task('default', function(done){
    console.info("");
    console.info("usage: gulp [command] [--prod]");
    console.info("");
    console.info("Options:");
    console.info("    --prod       If specified, will run clean-css and uglify when dumping the assets.");
    console.info("");
    console.info("Commands:");
    console.info("    copy         Copy the sources in the `config.copy` into a destination folder.");
    console.info("    clean        Cleans all configured destination files from expected output files.");
    console.info("    images       Dumps the sources in the `config.images` parameter from image files.");
    console.info("    less         Dumps the sources in the `config.less` parameter from LESS files.");
    console.info("    sass         Dumps the sources in the `config.sass` parameter from SCSS files.");
    console.info("    css          Dumps the sources in the `config.css` parameter from plain CSS files.");
    console.info("    js           Dumps the sources in the `config.js` parameter from plain JS files.");
    console.info("    dump         Executes all the above commands.");
    console.info("    watch        Executes 'dump', then watches all sources, and dumps all assets once any file is updated.");
    console.info("    test         Executes 'dump', then makes sure that all files in the sources directories are dumped correctly.");
    console.info("");
    done();
});

// Gulpfile with a single var as configuration.
// License: MIT
// Author: 2016 - Alex "Pierstoval" Rock Ancelet <alex@orbitale.io>
// Repository: https://github.com/Orbitale/Gulpfile
