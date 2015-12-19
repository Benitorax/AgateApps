<?php

namespace Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Tests\WebTestCase;

class AppContainerTest extends WebTestCase
{

    public function provideServiceIds()
    {
        return [
            'annotation_reader' => ['annotation_reader'],
            'assets.version_default' => ['assets.version_default'],
            'assets.context' => ['assets.context'],
            'assets.packages' => ['assets.packages'],
            'cache_clearer' => ['cache_clearer'],
            'cache_warmer' => ['cache_warmer'],
            'config_cache_factory' => ['config_cache_factory'],
            'corahn_rin_generator.sheets_manager' => ['corahn_rin_generator.sheets_manager'],
            'corahn_rin_generator.steps_loader' => ['corahn_rin_generator.steps_loader'],
            'data_collector.dump' => ['data_collector.dump'],
            'data_collector.form' => ['data_collector.form'],
            'data_collector.form.extractor' => ['data_collector.form.extractor'],
            'data_collector.request' => ['data_collector.request'],
            'data_collector.router' => ['data_collector.router'],
            'data_collector.translation' => ['data_collector.translation'],
            'debug.controller_resolver' => ['debug.controller_resolver'],
            'debug.debug_handlers_listener' => ['debug.debug_handlers_listener'],
            'debug.dump_listener' => ['debug.dump_listener'],
            'debug.event_dispatcher' => ['debug.event_dispatcher'],
            'debug.stopwatch' => ['debug.stopwatch'],
            'doctrine' => ['doctrine'],
            'doctrine.dbal.connection_factory' => ['doctrine.dbal.connection_factory'],
            'doctrine.dbal.default_connection' => ['doctrine.dbal.default_connection'],
            'doctrine.orm.default_entity_listener_resolver' => ['doctrine.orm.default_entity_listener_resolver'],
            'doctrine.orm.default_entity_manager' => ['doctrine.orm.default_entity_manager'],
            'doctrine.orm.default_listeners.attach_entity_listeners' => ['doctrine.orm.default_listeners.attach_entity_listeners'],
            'doctrine.orm.default_manager_configurator' => ['doctrine.orm.default_manager_configurator'],
            'doctrine.orm.validator.unique' => ['doctrine.orm.validator.unique'],
            'doctrine.orm.validator_initializer' => ['doctrine.orm.validator_initializer'],
            'doctrine_cache.providers.doctrine.orm.default_metadata_cache' => ['doctrine_cache.providers.doctrine.orm.default_metadata_cache'],
            'doctrine_cache.providers.doctrine.orm.default_query_cache' => ['doctrine_cache.providers.doctrine.orm.default_query_cache'],
            'doctrine_cache.providers.doctrine.orm.default_result_cache' => ['doctrine_cache.providers.doctrine.orm.default_result_cache'],
            'easyadmin.configurator' => ['easyadmin.configurator'],
            'easyadmin.form.type' => ['easyadmin.form.type'],
            'easyadmin.form.type.extension' => ['easyadmin.form.type.extension'],
            'easyadmin.listener.request_post_initialize' => ['easyadmin.listener.request_post_initialize'],
            'esteren_maps' => ['esteren_maps'],
            'file_locator' => ['file_locator'],
            'filesystem' => ['filesystem'],
            'form.csrf_provider' => ['form.csrf_provider'],
            'form.factory' => ['form.factory'],
            'form.registry' => ['form.registry'],
            'form.resolved_type_factory' => ['form.resolved_type_factory'],
            'form.type.birthday' => ['form.type.birthday'],
            'form.type.button' => ['form.type.button'],
            'form.type.checkbox' => ['form.type.checkbox'],
            'form.type.choice' => ['form.type.choice'],
            'form.type.collection' => ['form.type.collection'],
            'form.type.country' => ['form.type.country'],
            'form.type.currency' => ['form.type.currency'],
            'form.type.date' => ['form.type.date'],
            'form.type.datetime' => ['form.type.datetime'],
            'form.type.email' => ['form.type.email'],
            'form.type.entity' => ['form.type.entity'],
            'form.type.file' => ['form.type.file'],
            'form.type.form' => ['form.type.form'],
            'form.type.hidden' => ['form.type.hidden'],
            'form.type.integer' => ['form.type.integer'],
            'form.type.language' => ['form.type.language'],
            'form.type.locale' => ['form.type.locale'],
            'form.type.money' => ['form.type.money'],
            'form.type.number' => ['form.type.number'],
            'form.type.password' => ['form.type.password'],
            'form.type.percent' => ['form.type.percent'],
            'form.type.radio' => ['form.type.radio'],
            'form.type.range' => ['form.type.range'],
            'form.type.repeated' => ['form.type.repeated'],
            'form.type.reset' => ['form.type.reset'],
            'form.type.search' => ['form.type.search'],
            'form.type.submit' => ['form.type.submit'],
            'form.type.text' => ['form.type.text'],
            'form.type.textarea' => ['form.type.textarea'],
            'form.type.time' => ['form.type.time'],
            'form.type.timezone' => ['form.type.timezone'],
            'form.type.url' => ['form.type.url'],
            'form.type_extension.csrf' => ['form.type_extension.csrf'],
            'form.type_extension.form.data_collector' => ['form.type_extension.form.data_collector'],
            'form.type_extension.form.http_foundation' => ['form.type_extension.form.http_foundation'],
            'form.type_extension.form.validator' => ['form.type_extension.form.validator'],
            'form.type_extension.repeated.validator' => ['form.type_extension.repeated.validator'],
            'form.type_extension.submit.validator' => ['form.type_extension.submit.validator'],
            'form.type_guesser.doctrine' => ['form.type_guesser.doctrine'],
            'form.type_guesser.validator' => ['form.type_guesser.validator'],
            'fos_rest.body_listener' => ['fos_rest.body_listener'],
            'fos_rest.decoder.json' => ['fos_rest.decoder.json'],
            'fos_rest.decoder.jsontoform' => ['fos_rest.decoder.jsontoform'],
            'fos_rest.decoder.xml' => ['fos_rest.decoder.xml'],
            'fos_rest.decoder_provider' => ['fos_rest.decoder_provider'],
            'fos_rest.exception_format_negotiator' => ['fos_rest.exception_format_negotiator'],
            'fos_rest.format_listener' => ['fos_rest.format_listener'],
            'fos_rest.format_negotiator' => ['fos_rest.format_negotiator'],
            'fos_rest.inflector.doctrine' => ['fos_rest.inflector.doctrine'],
            'fos_rest.normalizer.camel_keys' => ['fos_rest.normalizer.camel_keys'],
            'fos_rest.param_fetcher_listener' => ['fos_rest.param_fetcher_listener'],
            'fos_rest.request.param_fetcher' => ['fos_rest.request.param_fetcher'],
            'fos_rest.request.param_fetcher.reader' => ['fos_rest.request.param_fetcher.reader'],
            'fos_rest.routing.loader.controller' => ['fos_rest.routing.loader.controller'],
            'fos_rest.routing.loader.processor' => ['fos_rest.routing.loader.processor'],
            'fos_rest.routing.loader.reader.action' => ['fos_rest.routing.loader.reader.action'],
            'fos_rest.routing.loader.reader.controller' => ['fos_rest.routing.loader.reader.controller'],
            'fos_rest.routing.loader.xml_collection' => ['fos_rest.routing.loader.xml_collection'],
            'fos_rest.routing.loader.yaml_collection' => ['fos_rest.routing.loader.yaml_collection'],
            'fos_rest.serializer.exception_wrapper_normalizer' => ['fos_rest.serializer.exception_wrapper_normalizer'],
            'fos_rest.serializer.exception_wrapper_serialize_handler' => ['fos_rest.serializer.exception_wrapper_serialize_handler'],
            'fos_rest.view.exception_wrapper_handler' => ['fos_rest.view.exception_wrapper_handler'],
            'fos_rest.view_handler' => ['fos_rest.view_handler'],
            'fos_rest.violation_formatter' => ['fos_rest.violation_formatter'],
            'fos_user.change_password.form' => ['fos_user.change_password.form'],
            'fos_user.change_password.form.type' => ['fos_user.change_password.form.type'],
            'fos_user.mailer' => ['fos_user.mailer'],
            'fos_user.profile.form' => ['fos_user.profile.form'],
            'fos_user.profile.form.type' => ['fos_user.profile.form.type'],
            'fos_user.registration.form' => ['fos_user.registration.form'],
            'fos_user.registration.form.type' => ['fos_user.registration.form.type'],
            'fos_user.resetting.form' => ['fos_user.resetting.form'],
            'fos_user.resetting.form.type' => ['fos_user.resetting.form.type'],
            'fos_user.security.interactive_login_listener' => ['fos_user.security.interactive_login_listener'],
            'fos_user.security.login_manager' => ['fos_user.security.login_manager'],
            'fos_user.user_manager' => ['fos_user.user_manager'],
            'fos_user.username_form_type' => ['fos_user.username_form_type'],
            'fos_user.util.email_canonicalizer' => ['fos_user.util.email_canonicalizer'],
            'fos_user.util.token_generator' => ['fos_user.util.token_generator'],
            'fos_user.util.user_manipulator' => ['fos_user.util.user_manipulator'],
            'fragment.handler' => ['fragment.handler'],
            'fragment.listener' => ['fragment.listener'],
            'fragment.renderer.esi' => ['fragment.renderer.esi'],
            'fragment.renderer.hinclude' => ['fragment.renderer.hinclude'],
            'fragment.renderer.inline' => ['fragment.renderer.inline'],
            'fragment.renderer.ssi' => ['fragment.renderer.ssi'],
            'framework_controller' => ['framework_controller'],
            'gedmo.listener.sluggable' => ['gedmo.listener.sluggable'],
            'gedmo.listener.timestampable' => ['gedmo.listener.timestampable'],
            'http_kernel' => ['http_kernel'],
            'ivory_ck_editor.config_manager' => ['ivory_ck_editor.config_manager'],
            'ivory_ck_editor.form.type' => ['ivory_ck_editor.form.type'],
            'ivory_ck_editor.plugin_manager' => ['ivory_ck_editor.plugin_manager'],
            'ivory_ck_editor.styles_set_manager' => ['ivory_ck_editor.styles_set_manager'],
            'ivory_ck_editor.template_manager' => ['ivory_ck_editor.template_manager'],
            'ivory_ck_editor.templating.helper' => ['ivory_ck_editor.templating.helper'],
            'ivory_ck_editor.twig_extension' => ['ivory_ck_editor.twig_extension'],
            'jms_serializer' => ['jms_serializer'],
            'jms_serializer.array_collection_handler' => ['jms_serializer.array_collection_handler'],
            'jms_serializer.constraint_violation_handler' => ['jms_serializer.constraint_violation_handler'],
            'jms_serializer.datetime_handler' => ['jms_serializer.datetime_handler'],
            'jms_serializer.doctrine_proxy_subscriber' => ['jms_serializer.doctrine_proxy_subscriber'],
            'jms_serializer.form_error_handler' => ['jms_serializer.form_error_handler'],
            'jms_serializer.handler_registry' => ['jms_serializer.handler_registry'],
            'jms_serializer.json_deserialization_visitor' => ['jms_serializer.json_deserialization_visitor'],
            'jms_serializer.json_serialization_visitor' => ['jms_serializer.json_serialization_visitor'],
            'jms_serializer.metadata_driver' => ['jms_serializer.metadata_driver'],
            'jms_serializer.naming_strategy' => ['jms_serializer.naming_strategy'],
            'jms_serializer.object_constructor' => ['jms_serializer.object_constructor'],
            'jms_serializer.php_collection_handler' => ['jms_serializer.php_collection_handler'],
            'jms_serializer.stopwatch_subscriber' => ['jms_serializer.stopwatch_subscriber'],
            'jms_serializer.templating.helper.serializer' => ['jms_serializer.templating.helper.serializer'],
            'jms_serializer.xml_deserialization_visitor' => ['jms_serializer.xml_deserialization_visitor'],
            'jms_serializer.xml_serialization_visitor' => ['jms_serializer.xml_serialization_visitor'],
            'jms_serializer.yaml_serialization_visitor' => ['jms_serializer.yaml_serialization_visitor'],
            'kernel' => ['kernel'],
            'kernel.class_cache.cache_warmer' => ['kernel.class_cache.cache_warmer'],
            'knp_menu.factory' => ['knp_menu.factory'],
            'knp_menu.listener.voters' => ['knp_menu.listener.voters'],
            'knp_menu.matcher' => ['knp_menu.matcher'],
            'knp_menu.menu_provider' => ['knp_menu.menu_provider'],
            'knp_menu.renderer.list' => ['knp_menu.renderer.list'],
            'knp_menu.renderer.twig' => ['knp_menu.renderer.twig'],
            'knp_menu.renderer_provider' => ['knp_menu.renderer_provider'],
            'knp_menu.voter.router' => ['knp_menu.voter.router'],
            'locale_listener' => ['locale_listener'],
            'logger' => ['logger'],
            'monolog.handler.console' => ['monolog.handler.console'],
            'monolog.handler.console_very_verbose' => ['monolog.handler.console_very_verbose'],
            'monolog.handler.debug' => ['monolog.handler.debug'],
            'monolog.handler.main' => ['monolog.handler.main'],
            'monolog.logger.doctrine' => ['monolog.logger.doctrine'],
            'monolog.logger.event' => ['monolog.logger.event'],
            'monolog.logger.php' => ['monolog.logger.php'],
            'monolog.logger.profiler' => ['monolog.logger.profiler'],
            'monolog.logger.request' => ['monolog.logger.request'],
            'monolog.logger.router' => ['monolog.logger.router'],
            'monolog.logger.security' => ['monolog.logger.security'],
            'monolog.logger.templating' => ['monolog.logger.templating'],
            'monolog.logger.translation' => ['monolog.logger.translation'],
            'nelmio_cors.cors_listener' => ['nelmio_cors.cors_listener'],
            'nelmio_cors.options_provider.config' => ['nelmio_cors.options_provider.config'],
            'orbitale_cms.listeners.layouts' => ['orbitale_cms.listeners.layouts'],
            'orbitale_cms.twig.extension' => ['orbitale_cms.twig.extension'],
            'pierstoval.api.listener' => ['pierstoval.api.listener'],
            'pierstoval.api.originchecker' => ['pierstoval.api.originchecker'],
            'pierstoval_tools.twig.json' => ['pierstoval_tools.twig.json'],
            'profiler' => ['profiler'],
            'profiler_listener' => ['profiler_listener'],
            'property_accessor' => ['property_accessor'],
            'request_stack' => ['request_stack'],
            'response_listener' => ['response_listener'],
            'router' => ['router'],
            'router_listener' => ['router_listener'],
            'routing.loader' => ['routing.loader'],
            'security.authentication.guard_handler' => ['security.authentication.guard_handler'],
            'security.authentication_utils' => ['security.authentication_utils'],
            'security.authorization_checker' => ['security.authorization_checker'],
            'security.context' => ['security.context'],
            'security.csrf.token_manager' => ['security.csrf.token_manager'],
            'security.encoder_factory' => ['security.encoder_factory'],
            'security.firewall' => ['security.firewall'],
            'security.firewall.map.context.dev' => ['security.firewall.map.context.dev'],
            'security.firewall.map.context.main' => ['security.firewall.map.context.main'],
            'security.password_encoder' => ['security.password_encoder'],
            'security.rememberme.response_listener' => ['security.rememberme.response_listener'],
            'security.secure_random' => ['security.secure_random'],
            'security.token_storage' => ['security.token_storage'],
            'security.user_checker.main' => ['security.user_checker.main'],
            'security.validator.user_password' => ['security.validator.user_password'],
            'sensio_distribution.security_checker' => ['sensio_distribution.security_checker'],
            'sensio_distribution.security_checker.command' => ['sensio_distribution.security_checker.command'],
            'sensio_framework_extra.cache.listener' => ['sensio_framework_extra.cache.listener'],
            'sensio_framework_extra.controller.listener' => ['sensio_framework_extra.controller.listener'],
            'sensio_framework_extra.converter.datetime' => ['sensio_framework_extra.converter.datetime'],
            'sensio_framework_extra.converter.doctrine.orm' => ['sensio_framework_extra.converter.doctrine.orm'],
            'sensio_framework_extra.converter.listener' => ['sensio_framework_extra.converter.listener'],
            'sensio_framework_extra.converter.manager' => ['sensio_framework_extra.converter.manager'],
            'sensio_framework_extra.security.listener' => ['sensio_framework_extra.security.listener'],
            'sensio_framework_extra.view.guesser' => ['sensio_framework_extra.view.guesser'],
            'sensio_framework_extra.view.listener' => ['sensio_framework_extra.view.listener'],
            'service_container' => ['service_container'],
            'session' => ['session'],
            'session.save_listener' => ['session.save_listener'],
            'session.storage.filesystem' => ['session.storage.filesystem'],
            'session.storage.native' => ['session.storage.native'],
            'session.storage.php_bridge' => ['session.storage.php_bridge'],
            'session_listener' => ['session_listener'],
            'stof_doctrine_extensions.uploadable.manager' => ['stof_doctrine_extensions.uploadable.manager'],
            'streamed_response_listener' => ['streamed_response_listener'],
            'swiftmailer.email_sender.listener' => ['swiftmailer.email_sender.listener'],
            'swiftmailer.mailer.default' => ['swiftmailer.mailer.default'],
            'swiftmailer.mailer.default.plugin.messagelogger' => ['swiftmailer.mailer.default.plugin.messagelogger'],
            'swiftmailer.mailer.default.spool' => ['swiftmailer.mailer.default.spool'],
            'swiftmailer.mailer.default.transport' => ['swiftmailer.mailer.default.transport'],
            'swiftmailer.mailer.default.transport.real' => ['swiftmailer.mailer.default.transport.real'],
            'templating' => ['templating'],
            'templating.filename_parser' => ['templating.filename_parser'],
            'templating.helper.assets' => ['templating.helper.assets'],
            'templating.helper.logout_url' => ['templating.helper.logout_url'],
            'templating.helper.router' => ['templating.helper.router'],
            'templating.helper.security' => ['templating.helper.security'],
            'templating.loader' => ['templating.loader'],
            'templating.name_parser' => ['templating.name_parser'],
            'test.client' => ['test.client'],
            'test.client.cookiejar' => ['test.client.cookiejar'],
            'test.client.history' => ['test.client.history'],
            'test.session.listener' => ['test.session.listener'],
            'translation.dumper.csv' => ['translation.dumper.csv'],
            'translation.dumper.ini' => ['translation.dumper.ini'],
            'translation.dumper.json' => ['translation.dumper.json'],
            'translation.dumper.mo' => ['translation.dumper.mo'],
            'translation.dumper.php' => ['translation.dumper.php'],
            'translation.dumper.po' => ['translation.dumper.po'],
            'translation.dumper.qt' => ['translation.dumper.qt'],
            'translation.dumper.res' => ['translation.dumper.res'],
            'translation.dumper.xliff' => ['translation.dumper.xliff'],
            'translation.dumper.yml' => ['translation.dumper.yml'],
            'translation.extractor' => ['translation.extractor'],
            'translation.extractor.php' => ['translation.extractor.php'],
            'translation.loader' => ['translation.loader'],
            'translation.loader.csv' => ['translation.loader.csv'],
            'translation.loader.dat' => ['translation.loader.dat'],
            'translation.loader.ini' => ['translation.loader.ini'],
            'translation.loader.json' => ['translation.loader.json'],
            'translation.loader.mo' => ['translation.loader.mo'],
            'translation.loader.php' => ['translation.loader.php'],
            'translation.loader.po' => ['translation.loader.po'],
            'translation.loader.qt' => ['translation.loader.qt'],
            'translation.loader.res' => ['translation.loader.res'],
            'translation.loader.xliff' => ['translation.loader.xliff'],
            'translation.loader.yml' => ['translation.loader.yml'],
            'translation.writer' => ['translation.writer'],
            'translator' => ['translator'],
            'translator.default' => ['translator.default'],
            'translator_listener' => ['translator_listener'],
            'twig' => ['twig'],
            'twig.controller.exception' => ['twig.controller.exception'],
            'twig.controller.preview_error' => ['twig.controller.preview_error'],
            'twig.exception_listener' => ['twig.exception_listener'],
            'twig.loader' => ['twig.loader'],
            'twig.profile' => ['twig.profile'],
            'twig.text_extension' => ['twig.text_extension'],
            'twig.translation.extractor' => ['twig.translation.extractor'],
            'uri_signer' => ['uri_signer'],
            'validator' => ['validator'],
            'validator.builder' => ['validator.builder'],
            'validator.email' => ['validator.email'],
            'validator.expression' => ['validator.expression'],
            'var_dumper.cli_dumper' => ['var_dumper.cli_dumper'],
            'var_dumper.cloner' => ['var_dumper.cloner'],
            'web_profiler.controller.exception' => ['web_profiler.controller.exception'],
            'web_profiler.controller.profiler' => ['web_profiler.controller.profiler'],
            'web_profiler.controller.router' => ['web_profiler.controller.router'],
            'controller_name_converter' => ['controller_name_converter'],
            'doctrine.dbal.logger.profiling.default' => ['doctrine.dbal.logger.profiling.default'],
            'esterenmaps.coordinates_manager' => ['esterenmaps.coordinates_manager'],
            'esterenmaps.directions_manager' => ['esterenmaps.directions_manager'],
            'esterenmaps.map_image_manager' => ['esterenmaps.map_image_manager'],
            'esterenmaps.tiles_manager' => ['esterenmaps.tiles_manager'],
            'fos_rest.request_matcher.0dfc4cce134bee15f08405cb5cea4845b13ff7d8c8f779004218432a2c552bd0cd9f9d27' => ['fos_rest.request_matcher.0dfc4cce134bee15f08405cb5cea4845b13ff7d8c8f779004218432a2c552bd0cd9f9d27'],
            'fos_rest.request_matcher.5bb28ce97f8a41c52ce937c9f72a24bfb9b41352f4dc7183c6d3aadaec59c7f5f473f3f9' => ['fos_rest.request_matcher.5bb28ce97f8a41c52ce937c9f72a24bfb9b41352f4dc7183c6d3aadaec59c7f5f473f3f9'],
            'fos_user.user_provider.username' => ['fos_user.user_provider.username'],
            'jms_serializer.unserialize_object_constructor' => ['jms_serializer.unserialize_object_constructor'],
            'router.request_context' => ['router.request_context'],
            'security.access.decision_manager' => ['security.access.decision_manager'],
            'security.authentication.manager' => ['security.authentication.manager'],
            'security.authentication.session_strategy' => ['security.authentication.session_strategy'],
            'security.authentication.trust_resolver' => ['security.authentication.trust_resolver'],
            'security.logout_url_generator' => ['security.logout_url_generator'],
            'security.role_hierarchy' => ['security.role_hierarchy'],
            'session.storage.metadata_bag' => ['session.storage.metadata_bag'],
            'stof_doctrine_extensions.listener.uploadable' => ['stof_doctrine_extensions.listener.uploadable'],
            'swiftmailer.mailer.default.transport.eventdispatcher' => ['swiftmailer.mailer.default.transport.eventdispatcher'],
            'templating.locator' => ['templating.locator'],
            'translator.selector' => ['translator.selector'],
        ];
    }

    /**
     * @dataProvider provideServiceIds
     *
     * @param string $serviceId
     */
    public function testContainer($serviceId)
    {
        $container = $this->getClient()->getContainer();
        try {
            $startedAt = microtime(true);
            $service = $container->get($serviceId);
            $elapsed = (microtime(true) - $startedAt) * 1000;
            $this->assertNotNull($service);
        } catch (InactiveScopeException $e) {
            $this->markTestSkipped('Skipped request-scope service "'.$serviceId.'".');
        }
    }

}