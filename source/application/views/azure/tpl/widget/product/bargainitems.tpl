[{oxscript add="$('a.js-external').attr('target', '_blank');"}]
[{assign var="currency" value=$oView->getActCurrency()}]
[{foreach from=$oView->getBargainArticleList() item=_product name=bargainList}]
    [{if $smarty.foreach.bargainList.first}]
        [{assign var='rsslinks' value=$oView->getRssLinks() }]
        [{oxid_include_widget cl="oxwArticleBox" currencySign=$currency->sign _parent=$oView->getClassName() nocookie=1 _navurlparams=$oViewConf->getNavUrlParams() iLinkType=$_product->getLinkType() anid=$_product->getId() isVatIncluded=$oView->isVatIncluded() rsslinks=$rsslinks iIteration=$smarty.foreach.bargainList.iteration sWidgetType=product sListType=bargainitem inlist=$_product->isInList() user=$oxcmp_user}]
    [{/if}]
[{/foreach}]
