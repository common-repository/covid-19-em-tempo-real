<?php
/**
 * COVID-19 em Tempo Real
 *
 * @wordpress-plugin
 * Plugin Name: COVID-19 em Tempo Real
 * Version:     1.1.3
 * Plugin URI:  https://vitaminasprime.com/coronavirus-tracker
 * Description: Primeiro plugin com os números do novo coronavírus COVID-19 em Tempo Real no Brasil e no Mundo em Português.
 * Author:      Vitaminas Prime
 * Author URI:  https://vitaminasprime.com
 * License:     GPL v3
 */


/**
 * This function is used to render the new coronavirus COVID-19 block
 *
 * @param $atts
 * @param  null  $content
 * @return string
 */

function VPRIME_COVID19_coronavirus_code_snippet( $atts, $content = null )
{
    $country            = isset( $atts['country'] ) ? $atts['country'] : 'BR';
    $show_country       = isset( $atts['show_country'] ) && is_numeric( $atts['show_country'] ) && $atts['show_country'] == 0 ? 0 : 1;
    $show_global        = isset( $atts['show_global'] ) && is_numeric( $atts['show_global'] ) && $atts['show_global'] == 0 ? 0 : 1;
    $show_list          = isset( $atts['show_list'] ) && is_numeric( $atts['show_list'] ) && $atts['show_list'] == 1 ? 1 : 0;
    $google_key         = isset( $atts['google_key'] ) && ! empty ( $atts['google_key'] ) ? trim ( $atts['google_key']) : null;
    $show_open_street   = isset( $atts['show_open_street'] ) && is_numeric( $atts['show_open_street'] ) && $atts['show_open_street'] == 1 ? 1 : 0;

    return "<div id='coronavirus-container'></div><script src='https://vitaminasprime.com/statistics/share/corona?container=coronavirus-container&country={$country}&show_country={$show_country}&show_global={$show_global}&show_list={$show_list}&google_key={$google_key}&show_open_street={$show_open_street}' async></script>";
}
add_shortcode('coronavirus_code_snippet', 'VPRIME_COVID19_coronavirus_code_snippet');


/**
 * This function is used to create a link on plugin list to its settings (tutorial)
 * @param $actions
 * @return array
 */
function VPRIME_COVID19_action_links($actions) {
    $actions['powered_settings'] = sprintf('<a href="%s">Configuração</a>', esc_url( admin_url( 'admin.php?page=ve_coronavirus')));
    return array_reverse($actions);
}
// to add settings link
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'VPRIME_COVID19_action_links');


/**
 * This function is used to create the setting pages
 */
function VE_COVID19_coronavirus_settings_init() {
    // register a new setting for "wporg" page
    register_setting( 've_coronavirus', 've_coronavirus_options' );

    // register a new section in the "wporg" page
    add_settings_section(
        've_coronavirus_section_developers',
        'Novo Coronavírus COVID-19 Code Snippet.',
        'VPRIME_COVID19_coronavirus_section_developers_cb',
        've_coronavirus'
    );
}
add_action( 'admin_init', 'VE_COVID19_coronavirus_settings_init' );

/**
 * custom option and settings:
 * callback functions
 */

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function VPRIME_COVID19_coronavirus_section_developers_cb( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>">Instruções para utilizar este plugin</p>
    <?php
}

/**
 * top level menu
 */
function VPRIME_COVID19_coronavirus_options_page() {
    // add top level menu page
    add_menu_page(
        'COVID-19 Configuração',
        'COVID-19',
        'manage_options',
        've_coronavirus',
        'VPRIME_COVID19_coronavirus_options_page_html',
        'dashicons-admin-site-alt');
}
add_action( 'admin_menu', 'VPRIME_COVID19_coronavirus_options_page' );

/**
 * This is the settings page (tutorial how to use the shortcode)
 */
function VPRIME_COVID19_coronavirus_options_page_html()
{
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // show error/update messages
    settings_errors( 'wporg_messages' );

    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

        <style type="text/css">
            .p-code {
                display: block; border-radius: 5px; border-top:20px solid #ddd; padding: 40px 20px; background: #333; color: #fff; white-space: nowrap; overflow-x: auto; font-size: 18px;
            }
            .p-code .p-code-parameter {color: #dd9f32 !important;}
            .p-code .p-code-value {color: #FF00FF !important;}

            .b-obs {
                display: block;
                color: #8DC63F;
                font-weight: 900;
                text-transform: uppercase;
            }
            .p-text {
                display: block;
                font-family: Roboto, Sans-serif; font-size: 16px;
                padding: 20px 0 0;
            }
            .p-notice {
                display: block;
                padding: 40px;
                margin: 20px;
                border: 1px dashed #8dc63f;
                border-radius: 10px;
                background: #8DC63F0D;
            }
            table.table {
                width: 100%;
                padding:0;
                margin: 20px 0;
                min-width: 768px;
            }

            table.table thead tr {
                background: #f2f2f2;
            }

            table.table thead th {
                padding: 20px 10px;
                border-top: 2px solid #ebebeb;
                border-bottom: 2px dashed #ebebeb;
                text-align: center;
                font-size:16px;
                font-weight: 900;
                white-space: nowrap;
            }

            table.table tbody td {
                padding: 20px 10px;
                border-bottom: 2px dashed #ebebeb;
                text-align: center;
            }
            table.table tbody td:first-child,
            table.table tbody td:last-child {
                text-align: left;
            }

            table.table tbody tr:nth-child(even) {
                background: #f8f8f8;
            }

            .btn.btn-primary {
                color: #fff;
                background: #8DC63F;
                padding: 15px 25px;
                text-decoration: none !important;
            }

            @media screen and (max-width: 767px) {
                .table-responsive {
                    overflow-x: auto;
                }
            }
        </style>
        <h2 style="font-size: 32px; font-family: Roboto, Sans-serif; font-weight: 900; text-transform: uppercase; color: #8dc63f; padding: 20px 0; text-align: center;">Coronav&iacute;rus COVID-19 Tracker<br /><span style="font-size: 18px;">Estat&iacute;sticas em Tempo Real</span></h2>
        <div class="row" style="margin: 0;">
            <div class="col-12" style="padding: 0;">
                <div style="padding: 25px;">
                    <p style="font-family: Roboto, Sans-serif; font-size: 16px;">Para você inserir as estatísticas do novo coronavírus COVID-19 no seu site é muito simples e rápido.</p>
                    <p style="font-family: Roboto, Sans-serif; font-size: 16px;">Basta você copiar o atalho abaixo e colocar onde você deseja mostrar no seu site. Lembre-se: ele poderá ser utilizado nos seus posts e páginas.</p>
                    <p class="p-code">
                        [coronavirus_code_snippet]
                    </p>
                    <p style="font-family: Roboto, Sans-serif; font-size: 16px;">Pronto uma vez feito isso você já poderá visualizar as estatísticas.</p>

                    <h3 style="font-size: 32px; font-family: Roboto, Sans-serif; font-weight: 900; text-transform: uppercase; color: #333; padding: 20px 0 0; text-align: center;">Coronav&iacute;rus Code Snippet com Open Street Map</h3>

                    <p style="font-family: Roboto, Sans-serif; font-size: 16px;">Agora você tem a possibilidade de utilizar as estatísticas no <b>Open Street Map</b> ou <b>Google Maps</b>.</p>

                    <p style="font-family: Roboto, Sans-serif; font-size: 16px;">Para você utilizar as estatísticas no Open Street Map é muito mai fácil e totalmente gratuíto, para isso basta você adicionar o parâmetro  <b>show_open_street=1</b> no seu shortcode e as estatísticas serão exibidas de forma correta. Veja um exemplo:</p>

                    <p class="p-code">
                        [coronavirus_code_snippet <span class="p-code-parameter">show_open_street=</span><span class="p-code-value">1</span>]
                    </p>

                    Mas, lembre-se que você também poderá utilizar com o Google Maps, iremos ver logo a seguir com o fazer a configuração. Para que não haja conflito na exibição dos mapas, lembre-se que você deve utilizar ou o parâmetro do Open Street Map ou o parâmetro do Google Maps, nunca utilize as duas chaves ao mesmo tempo, do contrário apenas o Google Maps será exibido.

                    <h3 style="font-size: 32px; font-family: Roboto, Sans-serif; font-weight: 900; text-transform: uppercase; color: #333; padding: 20px 0 0; text-align: center;">Coronav&iacute;rus Code Snippet com Google Maps</h3>

                    <p style="font-family: Roboto, Sans-serif; font-size: 16px;">O <b>Coronavírus Code Snippet</b> utiliza o Google Mapas para renderizar as informações de forma visual. Para que você possa fazer uso desta funcionalidade você precisará primeiramente obter a sua própria API KEY do Google Mapas, você poderá obter esta chave de acesso clicando no link abaixo.</p>

                    <p style="font-family: Roboto, Sans-serif; font-size: 16px; text-align: center; padding: 20px 0;"><a class="btn btn-primary" href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><span><span>Obter Chave do Google Mapas</span></span></a></p>

                    <p style="font-family: Roboto, Sans-serif; font-size: 16px;">Uma vez de posse da sua chave de acesso ao Google Mapas você deverá modificar um pouco o seu <b>Coronavírus Code Snippet</b> para que seja informada a chave de acesso do Google Mapas e permitir a renderização do mesmo.</p>

                    <p class="p-code">
                        [coronavirus_code_snippet <span class="p-code-parameter">google_key=</span><span class="p-code-value">SUA-CHAVE-DE-ACESSO-GOOGLE-MAPAS</span>]
                    </p>

                    <p style="font-family: Roboto, Sans-serif; font-size: 16px;">(*) Lembre-se de substituir o texto "<b>SUA-CHAVE-DE-ACESSO-GOOGLE-MAPAS</b>" pela chave obtida no Google</p>

                    <h3 style="font-size: 32px; font-family: Roboto, Sans-serif; font-weight: 900; text-transform: upper; color: #333; padding: 20px 0 0; text-align: center;">Resolvendo Problemas com o Google Mapas</h3>

                    <p style="font-family: Roboto, Sans-serif; font-size: 16px;">Não está no escopo desde projeto abordar eventuais problemas que venham a acontecer na renderização do Mapas do Google. Porém, há alguns problemas que são comuns no momento de usar a GOOGLE API KEY e que podem ser resolvidas verificando os seguintes detalhes:</p>

                    <ol style="padding: 0 0 0 35px;">
                        <li style="font-family: Roboto, Sans-serif; font-size: 16px; padding-bottom: 20px;">Restrição por IP ou domínio: se você está tentando utilizar uma chave pré-existente, verifique se o domínio e/ou página que você está utilizando está autorizada a renderizar o Mapas do Google</li>
                        <li style="font-family: Roboto, Sans-serif; font-size: 16px; padding-bottom: 20px;">Dados de cobrança: o Google atualmente exige que você preencha os dados de cobrança dentro do painel do Google, não se preocupe muito com esta questão, você apenas será cobrado se o acesso à esta página for muito grande, alguma coisa acima de 28.000 chamadas. Lembrando que os acessos feitos através de mobile é gratuito. Veja mais detalhes <a href="https://cloud.google.com/maps-platform/pricing/sheet?hl=en-US" target="_blank" >aqui</a>.</li>
                    </ol>
                </div>
            </div>
        </div>

        <h2 style="font-size: 32px; font-family: Roboto, Sans-serif; font-weight: 900; text-transform: upper; color: #333; padding: 40px 0; text-align: center;">Entenda como funciona o "Coronav&iacute;rus Code Snippet"?</h2>

        <p class="p-text">Ao inserir o código (atalho) acima, o mesmo será substituído automaticamente e será renderizado as estatísticas com os números de casos, mortes, casos graves e os ativos do país escolhido (veremos mais abaixo como selecionar o país), do mundo e uma listagem com as mesmas informações contidas no mapa.</p>

        <p class="p-text">Mas, além do parâmetro da chave de acesso do Google mostrado acima, você também poderá utilizar outros parâmetros para modificar as informações que serão exibidas. Veja abaixo quais os parâmetros que você poderá utilizar</p>


        <h2 style="font-size: 32px; font-family: Roboto, Sans-serif; font-weight: 900; text-transform: upper; color: #333; padding: 40px 0; text-align: center;">Parâmetros do "Coronav&iacute;rus Code Snippet"?</h2>

        <div class="table-responsive">
            <table class="table">
                <thead>
                <th scope="col">Parâmetro</th>
                <th scope="col">Valor Padrão</th>
                <th scope="col">Observação</th>
                </thead>
                <tbody>
                <tr>
                    <td><b>country</b></td>
                    <td>BR</td>
                    <td><p class="p-text">Por padrão o <b>Coronavirus Code Snippet</b> irá mostrar o país BRASIL. Porém, você poderá utilizar o código iso2 (2 dígitos) de outro país para que ele exiba os números referentes a este país.<br/><br/><b class="b-obs">Lembre-se: </b>Caso o código passado não seja um código válido o sistema irá usar como padrão o Brasil.<br/><br/>Veja mais abaixo a lista completa dos países aceitos neste parâmetro.</p></td>
                </tr>
                <tr>
                    <td><b>show_country</b></td>
                    <td>1</td>
                    <td><p class="p-text">Aqui você poderá setar o valor para <b>0</b> (ZERO) com a finalidade de não mostrar o bloco "Coronavírus no MEU_PAIS". Como padrão ele irá mostrar este bloco.</p></td>
                </tr>
                <tr>
                    <td><b>show_global</b></td>
                    <td>1</td>
                    <td><p class="p-text">Aqui você poderá setar o valor para <b>0</b> (ZERO) com a finalidade de não mostrar o bloco "Coronavírus no Mundo". Como padrão ele irá mostrar este bloco.</p></td>
                </tr>
                <tr>
                    <td><b>show_list</b></td>
                    <td>0</td>
                    <td><p class="p-text">Aqui você poderá setar o valor para <b>1</b> (UM) com a finalidade de mostrar o bloco "Coronavírus pelo Mundo", este bloco irá mostrar uma listagem de todos os países orderna pelos países que tem maior número de casos confirmados. Como padrão ele não irá mostrar este bloco.</p></td>
                </tr>
                <tr>
                    <td><b>show_open_street</b></td>
                    <td>0</td>
                    <td><p class="p-text">Este parâmetro irá renderizar as estatísticas da COVID-19 através do Open Street Maps, não é necessário configura chave de acesso, basta colocar o parâmetro com igual <b>1</b> (UM). Lembre-se de não utilizar o parâmetro do Google Maps com este parâmetro, pois, do contrário o Google Maps será exibido.</p></td>
                </tr>
                <tr>
                    <td><b>google_key</b></td>
                    <td>-</td>
                    <td><p class="p-text">Como já dissemos mais acima, se você quiser mostrar o Google Mapas no seu site ou blog, é necessário que você obtenha a sua própria chave de acesso à API de Mapas do Google. Caso você já tenha esta chave você poderá utilizar a mesma.<br/><br/>Saiba mais sobre a chave de acesso à API do Google Mapas <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" rel="nofollow" target="_blank">clicando aqui</a>.</p></td>
                </tr>
                </tbody>
            </table>
        </div>

        <h2 style="font-size: 32px; font-family: Roboto, Sans-serif; font-weight: 900; text-transform: upper; color: #333; padding: 40px 0; text-align: center;">Exemplo Completo</h2>

        <p class="p-text">Vamos agora ver um exemplo completo do <b>Coronavirus Code Snippet</b> onde todos os blocos são exibidos para o país Brasil. Você poderá utilizar o exemplo abaixo e adaptar de acordo com a sua necessidade. Lembre-se apenas de substituir o parâmetro "<b>google_key</b>" para a sua chave.</p>

        <p class="p-text"><b>UTILIZANDO O OPEN STREEET MAP</b></p>

        <p class="p-code">
            [coronavirus_code_snippet <span class="p-code-parameter">country=</span><span class="p-code-value">BR</span> <span class="p-code-parameter">show_country=</span><span class="p-code-value">1</span> <span class="p-code-parameter">show_global=</span><span class="p-code-value">1</span> <span class="p-code-parameter">show_list=</span><span class="p-code-value">1</span> <span class="p-code-parameter">show_open_street=</span><span class="p-code-value">1</span>]
        </p>

        <p class="p-text"><b>UTILIZANDO O GOOGLE MAPS</b></p>

        <p class="p-code">
            [coronavirus_code_snippet <span class="p-code-parameter">country=</span><span class="p-code-value">BR</span> <span class="p-code-parameter">show_country=</span><span class="p-code-value">1</span> <span class="p-code-parameter">show_global=</span><span class="p-code-value">1</span> <span class="p-code-parameter">show_list=</span><span class="p-code-value">1</span> <span class="p-code-parameter">google_key=</span><span class="p-code-value">GOOGLE_API_KEY</span>]
        </p>

        <h2 style="font-size: 32px; font-family: Roboto, Sans-serif; font-weight: 900; text-transform: upper; color: #333; padding: 40px 0; text-align: center;">Suporte e mais informações</h2>
        <p class="p-text">Os dados são atualizados a cada 15 minutos de acordo com o Centro de Controle de Doenças dos Estados Unidos (CDC) e a Organização Mundial da Saúde (WHO).</p>
        <p class="p-text">Em caso de dúvidas sobre o funcionamento do "<b>Coronavirus Code Snippet</b>", por favor, entre em contato conosco no email <a href="mailto:contato@vitaminasprime.com?subject=Coronavirus%20Code%20Snippet">contato@vitaminasprime.com</a>, retornaremos para você num prazo máximo de 24 horas.</p>
        <p class="p-text">O suporte é gratuíto e funciona 24/7.</p>

        <h2 style="font-size: 32px; font-family: Roboto, Sans-serif; font-weight: 900; text-transform: upper; color: #333; padding: 40px 0; text-align: center;">Lista de Países</h2>
        <p class="p-text">Veja abaixo a lista de países que você poderá utilizar no seu site, caso você deseje modificar o país padrão.</p>

        <p class="p-notice"><b class="b-obs">Lembre-se: </b> caso o país não tenha nenhum caso registrado do Coronavírus, o valor não será retornado para este país, tenha a certeza que você está monitorando um país que realmente tenha algum caso já confirmado.</p>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr><th style="text-align: center;">Código iso 2</th>
                <th style="text-align: left;">Nome</th>
            </tr></thead>
            <tbody>
            <tr>
                <td style="text-align: center;"><b>AD</b></td>
                <td>Andorra</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AE</b></td>
                <td>Emirados Árabes Unidos</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AF</b></td>
                <td>Afeganistão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AG</b></td>
                <td>Antiga e Barbuda</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AI</b></td>
                <td>Anguila</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AL</b></td>
                <td>Albânia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AM</b></td>
                <td>Arménia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AN</b></td>
                <td>Antilhas Holandesas</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AO</b></td>
                <td>Angola</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AQ</b></td>
                <td>Antártica</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AR</b></td>
                <td>Argentina</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AS</b></td>
                <td>Samoa Americana</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AT</b></td>
                <td>Áustria</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AU</b></td>
                <td>Austrália</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AW</b></td>
                <td>Aruba</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>AZ</b></td>
                <td>Azerbaijão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BA</b></td>
                <td>Bósnia e Herzegovina</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BB</b></td>
                <td>Barbados</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BD</b></td>
                <td>Bangladexe</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BE</b></td>
                <td>Bélgica</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BF</b></td>
                <td>Burquina Faso</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BG</b></td>
                <td>Bulgária</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BH</b></td>
                <td>Barém</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BI</b></td>
                <td>Burúndi</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BJ</b></td>
                <td>Benim</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BM</b></td>
                <td>Bermuda</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BN</b></td>
                <td>Brunei</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BO</b></td>
                <td>Bolívia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BR</b></td>
                <td>Brasil</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BS</b></td>
                <td>Bahamas</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BT</b></td>
                <td>Butão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BV</b></td>
                <td>Ilha Bouvet</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BW</b></td>
                <td>Botsuana</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BY</b></td>
                <td>Bielorrússia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>BZ</b></td>
                <td>Belize</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CA</b></td>
                <td>Canadá</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CC</b></td>
                <td>Ilhas Cocos</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CD</b></td>
                <td>República Democrática do Congo</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CF</b></td>
                <td>República Centro-Africana</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CG</b></td>
                <td>Congo-Brazzaville</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CH</b></td>
                <td>Suíça</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CI</b></td>
                <td>Costa do Marfim</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CK</b></td>
                <td>Ilhas Cook
                </td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CL</b></td>
                <td>Chile</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CM</b></td>
                <td>Camarões</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CN</b></td>
                <td>China</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CO</b></td>
                <td>Colômbia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CR</b></td>
                <td>Costa Rica</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CU</b></td>
                <td>Cuba</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CV</b></td>
                <td>Cabo Verde</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CW</b></td>
                <td>Curaçao</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CX</b></td>
                <td>Ilha do Natal</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CY</b></td>
                <td>Chipre</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CZ</b></td>
                <td>República Checa</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>CZ</b></td>
                <td>República Checa</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>DE</b></td>
                <td>Alemanha</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>DJ</b></td>
                <td>Jibuti</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>DK</b></td>
                <td>Dinamarca</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>DM</b></td>
                <td>Dominica</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>DO</b></td>
                <td>República Dominicana</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>DZ</b></td>
                <td>Argélia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>EC</b></td>
                <td>Equador</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>EE</b></td>
                <td>Estónia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>EG</b></td>
                <td>Egito</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>EH</b></td>
                <td>Saara Ocidental</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ER</b></td>
                <td>Eritreia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ES</b></td>
                <td>Espanha</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ET</b></td>
                <td>Etiópia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>EW</b></td>
                <td>Eswatini</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>FI</b></td>
                <td>Finlândia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>FJ</b></td>
                <td>Fiji</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>FK</b></td>
                <td>Ilhas Malvinas</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>FM</b></td>
                <td>Micronésia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>FO</b></td>
                <td>Ilhas Faroe</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>FR</b></td>
                <td>França</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GA</b></td>
                <td>Gabão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GB</b></td>
                <td>Reino Unido</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GD</b></td>
                <td>Granada</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GE</b></td>
                <td>Geórgia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GF</b></td>
                <td>Guiana Francesa</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GG</b></td>
                <td>Guernsey</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GH</b></td>
                <td>Gana</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GI</b></td>
                <td>Gibraltar</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GL</b></td>
                <td>Gronelândia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GM</b></td>
                <td>Gâmbia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GN</b></td>
                <td>Guiné</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GP</b></td>
                <td>Guadalupe</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GQ</b></td>
                <td>Guiné Equatorial</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GR</b></td>
                <td>Grécia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GS</b></td>
                <td>
                    Ilhas Geórgia do Sul</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GT</b></td>
                <td>Guatemala</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GU</b></td>
                <td>Guam</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GW</b></td>
                <td>Guiné-Bissau</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GY</b></td>
                <td>Guiana</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>GZ</b></td>
                <td>Faixa de Gaza</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>HK</b></td>
                <td>Hong Kong</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>HM</b></td>
                <td>Ilhas McDonald</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>HN</b></td>
                <td>Honduras</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>HR</b></td>
                <td>Croácia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>HT</b></td>
                <td>Haiti</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>HU</b></td>
                <td>Hungria</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ID</b></td>
                <td>Indonésia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>IE</b></td>
                <td>Irlanda</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>IL</b></td>
                <td>Israel</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>IM</b></td>
                <td>Ilha do Homem</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>IN</b></td>
                <td>Índia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>IO</b></td>
                <td>Território Britânico do Oceano Índico</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>IQ</b></td>
                <td>Iraque</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>IR</b></td>
                <td>Irã</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>IS</b></td>
                <td>Islândia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>IT</b></td>
                <td>Itália</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>JE</b></td>
                <td>Jersey</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>JM</b></td>
                <td>Jamaica</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>JO</b></td>
                <td>Jordânia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>JP</b></td>
                <td>Japão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KE</b></td>
                <td>Quénia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KG</b></td>
                <td>Quirguistão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KH</b></td>
                <td>Camboja</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KI</b></td>
                <td>Quiribáti</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KM</b></td>
                <td>Comores</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KN</b></td>
                <td>São Cristóvão e Neves</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KP</b></td>
                <td>Coreia do Norte</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KR</b></td>
                <td>Coreia do Sul</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KW</b></td>
                <td>Cuaite</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KY</b></td>
                <td>Ilhas Caiman</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>KZ</b></td>
                <td>Cazaquistão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LA</b></td>
                <td>Laus</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LB</b></td>
                <td>Líbano</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LC</b></td>
                <td>Santa Lúcia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LI</b></td>
                <td>Listenstaine</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LK</b></td>
                <td>Sri Lanca</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LR</b></td>
                <td>Libéria</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LS</b></td>
                <td>Lesoto</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LT</b></td>
                <td>Lituânia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LU</b></td>
                <td>Luxemburgo</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LV</b></td>
                <td>Letónia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>LY</b></td>
                <td>Líbia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MA</b></td>
                <td>Marrocos</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MC</b></td>
                <td>Mónaco</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MD</b></td>
                <td>Moldávia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ME</b></td>
                <td>Montenegro</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MG</b></td>
                <td>Madagáscar</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MH</b></td>
                <td>Ilhas Marechal</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MK</b></td>
                <td>Macedónia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ML</b></td>
                <td>Mali</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MM</b></td>
                <td>Mianmar</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MN</b></td>
                <td>Mongólia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MO</b></td>
                <td>Macau</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MP</b></td>
                <td>Ilhas Marianas do Norte</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MQ</b></td>
                <td>Martinica</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MR</b></td>
                <td>Mauritânia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MS</b></td>
                <td>Montserrat</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MT</b></td>
                <td>Malta</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MU</b></td>
                <td>Maurícia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MV</b></td>
                <td>Maldivas</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MW</b></td>
                <td>Maláui</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MX</b></td>
                <td>México</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MY</b></td>
                <td>Malásia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>MZ</b></td>
                <td>Moçambique</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NA</b></td>
                <td>Namíbia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NC</b></td>
                <td>Nova Caledônia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NE</b></td>
                <td>Níger</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NF</b></td>
                <td>Ilha Norfolk</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NG</b></td>
                <td>Nigéria</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NI</b></td>
                <td>Nicarágua</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NL</b></td>
                <td>Países Baixos</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NO</b></td>
                <td>Noruega</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NP</b></td>
                <td>Nepal</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NR</b></td>
                <td>Nauru</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NU</b></td>
                <td>Niue</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>NZ</b></td>
                <td>Nova Zelândia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>OM</b></td>
                <td>Omã</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PA</b></td>
                <td>Panamá</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PE</b></td>
                <td>Peru</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PF</b></td>
                <td>Polinésia Francesa</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PG</b></td>
                <td>Papua Nova Guiné</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PH</b></td>
                <td>Filipinas</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PK</b></td>
                <td>Paquistão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PL</b></td>
                <td>Polônia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PM</b></td>
                <td>São Pedro e Miquelon</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PN</b></td>
                <td>Ilhas Pitcairn</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PR</b></td>
                <td>Porto Rico</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PS</b></td>
                <td>Estado da Palestina</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PT</b></td>
                <td>Portugal</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PW</b></td>
                <td>Palau</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>PY</b></td>
                <td>Paraguai</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>QA</b></td>
                <td>Catar</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>RE</b></td>
                <td>Reunião</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>RO</b></td>
                <td>Roménia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>RS</b></td>
                <td>Sérvia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>RU</b></td>
                <td>Rússia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>RW</b></td>
                <td>Ruanda</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SA</b></td>
                <td>Arábia Saudita</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SB</b></td>
                <td>Salomão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SC</b></td>
                <td>Seicheles</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SD</b></td>
                <td>Sudão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SE</b></td>
                <td>Suécia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SG</b></td>
                <td>Singapura</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SH</b></td>
                <td>Santa Helena</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SI</b></td>
                <td>Eslovénia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SJ</b></td>
                <td>Svalbard e Jan Mayen</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SK</b></td>
                <td>Eslováquia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SL</b></td>
                <td>Serra Leoa</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SM</b></td>
                <td>São Marinho</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SN</b></td>
                <td>Senegal</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SO</b></td>
                <td>Somália</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SR</b></td>
                <td>Suriname</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ST</b></td>
                <td>São Tomé e Príncipe</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SV</b></td>
                <td>El Salvador</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SY</b></td>
                <td>Síria</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>SZ</b></td>
                <td>Suazilândia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TC</b></td>
                <td>Ilhas Turcas e Caicos</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TD</b></td>
                <td>Chade</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TF</b></td>
                <td>Territórios franceses do sul</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TG</b></td>
                <td>Togo</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TH</b></td>
                <td>Tailândia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TJ</b></td>
                <td>Tajiquistão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TK</b></td>
                <td>Toquelau</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TL</b></td>
                <td>Timor-Leste</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TM</b></td>
                <td>Turcomenistão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TN</b></td>
                <td>Tunísia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TO</b></td>
                <td>Tonga</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TR</b></td>
                <td>Turquia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TT</b></td>
                <td>Trindade e Tobago</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TV</b></td>
                <td>Tuvalu</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TW</b></td>
                <td>Taiuã</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>TZ</b></td>
                <td>Tanzânia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>UA</b></td>
                <td>Ucrânia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>UG</b></td>
                <td>Uganda</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>US</b></td>
                <td>Estados Unidos</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>US</b></td>
                <td>Ilhas do Canal (EUA)</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>UY</b></td>
                <td>Uruguai</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>UZ</b></td>
                <td>Usbequistão</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>VA</b></td>
                <td>Vaticano</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>VC</b></td>
                <td>São Vicente e Granadinas</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>VE</b></td>
                <td>Venezuela</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>VG</b></td>
                <td>Ilhas Virgens Britânicas</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>VI</b></td>
                <td>Ilhas Virgens Americanas</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>VN</b></td>
                <td>Vietnã</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>VU</b></td>
                <td>Vanuatu</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>WF</b></td>
                <td>Wallis e Futuna</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>WS</b></td>
                <td>Samoa</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>XK</b></td>
                <td>Kosovo</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>YE</b></td>
                <td>Iémen</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>YT</b></td>
                <td>Mayotte</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ZA</b></td>
                <td>África do Sul</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ZM</b></td>
                <td>Zâmbia</td>
            </tr>
            <tr>
                <td style="text-align: center;"><b>ZW</b></td>
                <td>Zimbábue</td>
            </tr>
            </tbody>
        </table>
    </div>

    <?php
}