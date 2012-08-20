// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// BBCode tags example
// http://en.wikipedia.org/wiki/Bbcode
// ----------------------------------------------------------------------------
// Feel free to add more tags
// ----------------------------------------------------------------------------
mySettings = {
	markupSet: [
		{name:'Жирный', className:"bold", key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name:'Курсив', className:"italic", key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name:'Подчеркнутый', className:"underline", key:'U', openWith:'[u]', closeWith:'[/u]'},
		{separator:'---------------' },
		{name:'Заголовок 1', className:"h1", key:'1', openWith:'[h1]', closeWith:'[/h1]' },
		{name:'Заголовок 2', className:"h2", key:'2', openWith:'[h2]', closeWith:'[/h2]' },
		{separator:'---------------' },
		{name:'Картинка', className:"picture", key:'P', replaceWith:'[img][![Введите ссылку на картинку]!][/img]'},
		{name:'Ссылка', className:"link", key:'L', openWith:'[url=[![Ссылка]!]]', closeWith:'[/url]', placeHolder:'Текст ссылки'},
		{separator:'---------------' },
		{name:'Ненумерованный список', className:"ul", openWith:'[list]\n', closeWith:'\n[/list]'},
		{name:'Нумерованный список', className:"ol", openWith:'[list=[![Начальное число]!]]\n', closeWith:'\n[/list]'},
		{name:'Элемент списка', className:"li", openWith:'[*] '},
		{separator:'---------------' },
		{name:'Почистить код', className:"clean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
		{name:'Предпросмотр', className:"preview", call:'preview' }
	]
};