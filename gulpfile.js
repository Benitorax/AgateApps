/*
* There here is YOUR config
* This var is an example of the assets you can use in your own application.
* The array KEYS correspond to the OUTPUT files/directories,
* The array VALUES contain a LIST OF SOURCE FILES
*/
var config = {

    // The base output directory for all your assets
    "output_directory": "web",

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
        "fonts/roboto": [
            "node_modules/materialize-css/dist/fonts/roboto/*"
        ],
        "fonts/": [
            "node_modules/font-awesome/fonts/*",
            "node_modules/bootstrap/dist/fonts/*"
        ],
        "images": [
            "node_modules/leaflet/dist/images/*"
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
        "src/Esteren/PortalBundle/Resources/assets/less/*.less",
        "src/Esteren/PortalBundle/Resources/assets/css/*.css",
        "src/Esteren/PortalBundle/Resources/assets/sass/*.scss",
        "src/Esteren/PortalBundle/Resources/assets/sass/theme_components/*.scss",
        "src/CorahnRin/CorahnRinBundle/Resources/public/generator/css/*.less"
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
            "src/EsterenMaps/MapsBundle/Resources/public/img/markerstypes/*"
        ],
        "img/generator": [
            "src/CorahnRin/CorahnRinBundle/Resources/public/img/*"
        ],
        "img/agate": [
            "src/AgateBundle/Resources/assets/images/*"
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
        "css/global.css": [
            "src/Esteren/PortalBundle/Resources/assets/less/_main.less"
        ],
        "css/maps_lib.css": [
            "src/EsterenMaps/MapsBundle/Resources/public/less/maps.less"
        ],
        "css/admin.css": [
            "src/AdminBundle/Resources/public/less/admin.less"
        ],
        "css/generator.css": [
            "src/CorahnRin/CorahnRinBundle/Resources/public/generator/css/main_steps.less"
        ],
        "css/character_details.css": [
            "src/CorahnRin/CorahnRinBundle/Resources/public/generator/css/character_details.less"
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
        "css/global_mat.css": [
            "src/Esteren/PortalBundle/Resources/assets/sass/main.scss"
        ],
        "css/agate.css": [
            "src/AgateBundle/Resources/assets/sass/theme.scss"
        ],
        "css/white_layout.css": [
            "src/Esteren/PortalBundle/Resources/assets/sass/white_layout.scss"
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
            "src/Esteren/PortalBundle/Resources/assets/css/initializer.css"
        ]
    },

    /**
     * All files from this section are just concatenated and dumped into a JS file.
     * If using "--prod" in command line, will minify with "uglyflyjs".
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
            "node_modules/bootstrap/js/button.js",
            "node_modules/bootstrap/js/collapse.js",
            "node_modules/bootstrap/js/dropdown.js",
            "node_modules/bootstrap/js/modal.js",
            "node_modules/bootstrap/js/tooltip.js",
            "node_modules/bootstrap/js/popover.js",
            "node_modules/bootstrap/js/transition.js",
            "node_modules/bootstrap/js/tab.js",
            "src/Esteren/PortalBundle/Resources/assets/js/corahn_rin.js",
            "src/Esteren/PortalBundle/Resources/assets/js/helpers.js"
        ],
        "js/global_mat.js": [
            "node_modules/materialize-css/dist/js/materialize.js",
            "src/Esteren/PortalBundle/Resources/assets/js/helpers.js",
            "src/Esteren/PortalBundle/Resources/assets/js/global_mat.js"
        ],
        "js/maps_lib.js": [
            "node_modules/leaflet/dist/leaflet-src.js",
            "./web/components/leaflet-sidebar/src/L.Control.Sidebar.js",
            "./web/components/leaflet-draw/dist/leaflet.draw-src.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/1_EsterenMap.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/1_EsterenMap.load.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/2_EsterenMap_CRS_XY.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/2_EsterenMap_directions.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/2_EsterenMap_LatLngBounds.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/3_EsterenMap_ActivateLeafletDraw.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/3_EsterenMap_options.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/4_EsterenMap_addMarker.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/4_EsterenMap_addPolygon.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/4_EsterenMap_addPolyline.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/4_EsterenMap_filters.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/4_EsterenMap_search_engine.js",
            "src/EsterenMaps/MapsBundle/Resources/public/js/EsterenMap/5_EsterenMap_mapEdit.js"
        ],
        "js/generator.js": [
            "src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/main_steps.js"
        ],
        "js/step_03_birthplace.js": ["src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_03_birthplace.js"],
        "js/step_11_advantages.js": [
            "src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_11_advantage_class.js",
            "src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_11_functions.js",
            "src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_11_advantages_process.js"
        ],
        "js/step_13_primary_domains.js": ["src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_13_primary_domains.js"],
        "js/step_14_use_domain_bonuses.js": ["src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_14_use_domain_bonuses.js"],
        "js/step_15_domains_spend_exp.js": ["src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_15_domains_spend_exp.js"],
        "js/step_16_disciplines.js": ["src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_16_disciplines.js"],
        "js/step_17_combat_arts.js": ["src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_17_combat_arts.js"],
        "js/step_18_equipment.js": ["src/CorahnRin/CorahnRinBundle/Resources/public/generator/js/step_18_equipment.js"]
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

    let size = 0, key;
    for (key in object) {
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

    let key;
    for (key in object) {
        if (object.hasOwnProperty(key)) {
            callback.apply(object, [key, object[key]]);
        }
    }
    return object;
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
var gulp       = require('gulp4');
var gulpif     = require('gulp-if');
var watch      = require('gulp-watch');
var concat     = require('gulp-concat');
var uglyfly    = require('gulp-uglyfly');
var cleancss   = require('gulp-clean-css');
var sourcemaps = require('gulp-sourcemaps');

// Load other extensions only when having specific components. Saves memory & time execution.
var less     = hasLess   ? require('gulp-less')     : function(){ return {}; };
var sass     = hasSass   ? require('gulp-sass')     : function(){ return {}; };
var imagemin = hasImages ? require('gulp-imagemin') : function(){ return {}; };

/*************** Gulp tasks ***************/

/**
 * Dumps the LESS assets
 */
gulp.task('less', function(done) {
    "use strict";

    let list = config.less,
        outputDir = config.output_directory+'/',
        assets_output, assets, pipes, i, l
    ;
    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pipes = gulp
            .src(assets)
            .pipe(less())
            .pipe(concat(assets_output))
            .pipe(gulpif(isProd, cleancss()))
            .pipe(concat(assets_output))
            .pipe(gulp.dest(outputDir))
        ;

        console.info(" [file+] "+assets_output+" >");
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    pipes ? pipes.on('end', function(){done();}) : done();
});

/**
 * Dumps the SASS assets
 */
gulp.task('sass', function(done) {
    "use strict";

    let list = config.sass,
        outputDir = config.output_directory+'/',
        assets_output, assets, pipes, i, l
    ;
    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pipes = gulp
            .src(assets)
            .pipe(sass().on('error', sass.logError))
            .pipe(concat(assets_output))
            .pipe(gulpif(isProd, cleancss()))
            .pipe(concat(assets_output))
            .pipe(gulp.dest(outputDir))
        ;

        console.info(" [file+] "+assets_output+" >");
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    pipes ? pipes.on('end', function(){done();}) : done();
});

/**
 * Simply copy files into another directory.
 * Useful for simple "dist" files from node_modules directory, for example.
 */
gulp.task('copy', function(done) {
    "use strict";

    let list = config.copy,
        outputDir = config.output_directory+'/',
        assets_output, assets, pipes, i, l
    ;
    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pipes = gulp
            .src(assets)
            .pipe(gulp.dest(outputDir + assets_output))
        ;

        console.info(" [file+] "+assets_output+" >");
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    pipes ? pipes.on('end', function(){done();}) : done();
});

/**
 * Compress images.
 * Thanks to @docteurklein.
 */
gulp.task('images', function(done) {
    "use strict";

    let list = config.images,
        outputDir = config.output_directory+'/',
        assets_output, assets, pipes, i, l
    ;
    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pipes = gulp
            .src(assets)
            .pipe(imagemin([
                imagemin.gifsicle({interlaced: true}),
                imagemin.jpegtran({progressive: true}),
                imagemin.optipng({optimizationLevel: 7}),
                imagemin.svgo({plugins: [{removeViewBox: true}]})
            ], { verbose: true }))
            .pipe(gulp.dest(outputDir + assets_output))
        ;

        console.info(" [file+] "+assets_output+" >");
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    pipes ? pipes.on('end', function(){done();}) : done();
});

/**
 * Dumps the CSS assets.
 */
gulp.task('css', function(done) {
    "use strict";

    let list = config.css,
        outputDir = config.output_directory+'/',
        assets_output, assets, pipes, i, l
    ;
    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pipes = gulp
            .src(assets)
            .pipe(concat(assets_output))
            .pipe(gulpif(isProd, cleancss()))
            .pipe(concat(assets_output))
            .pipe(gulp.dest(outputDir))
        ;

        console.info(" [file+] "+assets_output+" >");
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    pipes ? pipes.on('end', function(){done();}) : done();
});

/**
 * Dumps the JS assets
 */
gulp.task('js', function(done) {
    "use strict";

    let list = config.js,
        outputDir = config.output_directory+'/',
        assets_output, assets, pipes, i, l
    ;
    for (assets_output in list) {
        if (!list.hasOwnProperty(assets_output)) { continue; }
        assets = list[assets_output];
        pipes = gulp
            .src(assets)
            .pipe(sourcemaps.init())
            .pipe(concat({path: assets_output, cwd: ''}))
            .pipe(gulpif(isProd, uglyfly()))
            .pipe(gulp.dest(outputDir))
        ;

        console.info(" [file+] "+assets_output+" >");
        for (i = 0, l = assets.length; i < l; i++) {
            console.info("       > "+assets[i]);
        }
    }

    pipes ? pipes.on('end', function(){done();}) : done();
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
gulp.task('watch', gulp.series('dump', gulp.parallel(function(done) {
    "use strict";

    let files_less = [],
        files_images = [],
        files_copy = [],
        files_css = [],
        files_sass = [],
        files_js = [],
        callback = function(event) {
            console.log('File "' + event.path + '" updated');
        },
        other_files_to_watch = config.files_to_watch || [],
        files_to_watch = []
    ;

    console.info('Night gathers, and now my watch begins...');

    GulpfileHelpers.objectForEach(config.images, function(key, images){
        files_images.push(images);
        files_to_watch.push(images);
    });
    GulpfileHelpers.objectForEach(config.copy, function(key, copy){
        files_copy.push(copy);
        files_to_watch.push(copy);
    });
    GulpfileHelpers.objectForEach(config.less, function(key, less){
        files_less.push(less);
        files_to_watch.push(less);
    });
    GulpfileHelpers.objectForEach(config.sass, function(key, sass){
        files_sass.push(sass);
        files_to_watch.push(sass);
    });
    GulpfileHelpers.objectForEach(config.css, function(key, css){
        files_css.push(css);
        files_to_watch.push(css);
    });
    GulpfileHelpers.objectForEach(config.js, function(key, js){
        files_js.push(js);
        files_to_watch.push(js);
    });

    if (files_to_watch.length) {
        console.info('Watching file(s):');
        // Flatten the array
        files_to_watch = [].concat.apply([], files_to_watch).sort();
        console.info("       > "+files_to_watch.join("\n       > "));
    }

    if (other_files_to_watch.length) {
        gulp.watch(other_files_to_watch, gulp.parallel('dump')).on('change', callback);
    }
    if (hasImages) {
        gulp.watch(files_images, gulp.parallel('images')).on('change', callback);
    }
    if (hasCopy) {
        gulp.watch(files_copy, gulp.parallel('copy')).on('change', callback);
    }
    if (hasLess) {
        gulp.watch(files_less, gulp.parallel('less')).on('change', callback);
    }
    if (hasSass) {
        gulp.watch(files_sass, gulp.parallel('sass')).on('change', callback);
    }
    if (hasCss) {
        gulp.watch(files_css, gulp.parallel('css')).on('change', callback);
    }
    if (hasJs) {
        gulp.watch(files_js, gulp.parallel('js')).on('change', callback);
    }

    done();
})));

/**
 * Small user guide
 */
gulp.task('default', function(done){
    console.info("");
    console.info("usage: gulp [command] [--prod]");
    console.info("");
    console.info("Options:");
    console.info("    --prod       If specified, will run clean-css and uglyfyjs when dumping the assets.");
    console.info("");
    console.info("Commands:");
    console.info("    copy         Copy the sources in the `config.copy` into a destination folder.");
    console.info("    images       Dumps the sources in the `config.images` parameter from image files.");
    console.info("    less         Dumps the sources in the `config.less` parameter from LESS files.");
    console.info("    sass         Dumps the sources in the `config.sass` parameter from SCSS files.");
    console.info("    css          Dumps the sources in the `config.css` parameter from plain CSS files.");
    console.info("    js           Dumps the sources in the `config.js` parameter from plain JS files.");
    console.info("    dump         Executes all the above commands.");
    console.info("    watch        Executes 'dump', then watches all sources, and dumps all assets once any file is updated.");
    console.info("");
    done();
});

// Gulpfile with a single var as configuration.
// License: MIT
// Author: 2016 - Alex "Pierstoval" Rock Ancelet <alex@orbitale.io>
// Repository: https://github.com/Orbitale/Gulpfile
