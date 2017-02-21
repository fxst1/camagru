

		var start_x = 0;
		var	start_y = 0;
		var	end_x = start_x + 500;
		var	end_y = start_y + 376;

		var	W = 100;
		var	H = 100;
		var	X = 0;
		var	Y = 0;

		var	order = null;
		var	objs = new Array();

		var	selection = null;

		function	delUpload()
		{
			var		tag = document.getElementById('video-cnt');
			tag.innerHTML = "<video id=\"cam\" ></video>";
			var		tag = document.getElementById('video');
			tag.innerHTML = "";
			putFiltres();
		}

		function	littleResetBorder(value, id)
		{
			var doc = document.getElementById('filter' + id);
			if (doc)
			{
				if (id == selection)
					doc.style.borderColor = 'black';
				else
					doc.style.borderColor = 'white';
			}
		}

		function	resetBorders()
		{
			objs.forEach(littleResetBorder);
		}

		function	change(what, value, id)
		{
			var	it = document.getElementById('nbox' + id);

			var	v = parseInt(value.value);

			var	xa = parseInt(it.style.left);
			var	ya = parseInt(it.style.top);

			if (what == 'w' && (xa + v < end_x))
				it.style.width = v + 'px';
			else if (what == 'h' && (ya + v < end_y))
				it.style.height = v + 'px';
			else if (what == 'x' && (v > start_x && (v + parseInt(it.style.width)) < end_x))
				it.style.left = v + 'px';
			else if (what == 'y' && (v > start_y && (v + parseInt(it.style.height)) < end_y))
				it.style.top = v + 'px';
			objs[id] = [it.style.width, it.style.height, it.style.left, it.style.top];
		}

		function	createSettings(id, w, h, x, y)
		{
			var	balise = document.getElementById('form');
			var	set = document.createElement('div');

			var	row1 = '<tr><th>width</th> <td><input onchange="change(\'w\', this, ' + id + ')" type="number" value="' + parseInt(w) + '"></td></tr>';
			var	row2 = '<tr><th>height</th> <td><input onchange="change(\'h\', this, ' + id + ')" type="number" value="' + parseInt(h) + '"></td></tr>';
			var row3 = '<tr><th>x</th> <td><input onchange="change(\'x\', this, ' + id + ')" type="number" value="' + parseInt(x) + '"></td></tr>';
			var row4 = '<tr><th>y</th> <td><input onchange="change(\'y\', this, ' + id + ')" type="number" value="' + parseInt(y) + '"></td></tr>';

			balise.appendChild(set);
			set.setAttribute("id", "settings");
			set.innerHTML = '<hr /><table>' + row1 + row2 + row3 + row4 + '</table><hr />';
		}

		function	deleteSettings()
		{
			var	balise = document.getElementById('form');
			var	set = document.getElementById('settings');
			
			if (set)
				balise.removeChild(set);
		}

		function	createBtn()
		{
			var	elemt = document.createElement('button');
			var	doc = document.getElementById('main-section');

			if (!document.getElementById('ok'))
			{
				doc.appendChild(elemt);
				elemt.innerHTML = "Prendre une photo";
				elemt.setAttribute("id", "ok");
				elemt.setAttribute("onclick", "takePicture()");
			}
		}

		function	delBtn()
		{
			var	elemt = document.getElementById('ok');
			var	doc = document.getElementById('main-section');

			if (elemt)
				doc.removeChild(elemt);
		}

		function	resetSettings(id)
		{
			deleteSettings();
			if (objs[id])
				createSettings(id, objs[id][0], objs[id][1], objs[id][2], objs[id][3]);
			else
				createSettings(id, W, H, X, Y);
		}

		function	set(id)
		{
			selection = id;
			if (selection != null && document.getElementById('settings') == null)
				createSettings(selection, objs[selection][0], objs[selection][1], objs[selection][2], objs[selection][3], objs[selection][4]);
			else if (selection == null)
				deleteSettings();
			else
				resetSettings(selection);
			resetBorders();
			needBtn();
		}

		function	select(id, data)
		{
			var	top = Y + 'px';
			var left = X + 'px';
			var	w = W + 'px';
			var	h = H + 'px';

			var	balise = document.getElementById('filter' + id);
			var	newImg = '<img src="' + data + '" id="nbox' + id + '" style="position:absolute;top:' + top + ";left:" + left + ';width:' + w + ';height:' + h + ';" id="n' + id + '">';

			if (balise.className == "divFilter select use")
			{
				deleteOrder(id);
				selection = null;
				document.getElementById('video').removeChild(document.getElementById('nbox' + id));
				balise.className = 'divFilter unselect unuse';
				objs[id] = null;
				deleteOrder(id);
			}
			else
			{
				appendOrder(id);
				selection = id;
				document.getElementById('video').innerHTML += newImg;
				document.getElementById('nbox' + id).setAttribute("onclick", "set(" + id + ")")
				balise.className = 'divFilter select use';
				objs[id] = new Array(w, h, left, top);
				appendOrder(id);
			}

			if (selection != null && document.getElementById('settings') == null)
				createSettings(selection, w, h, left, top);
			else if (selection == null)
				deleteSettings();
			else
				resetSettings(selection);

			resetBorders();
			needBtn();
		}

		function	needBtn()
		{
			var	ok = 0;
			for (var o in objs)
			{
				if (objs[o] != null && objs[o] != "")
				{
					ok = 1;
					break ;
				}
			}
			if (ok)
				createBtn();
			else
				delBtn();
		}

		function	deleteUpload()
		{
			var	balise = document.getElementById('video');
			var	form = document.getElementById('upload-form');
			balise.removeChild(form);
		}

		function	correctObjs()
		{
			obj = new Array();
			for (var o in objs)
			{
				if (objs[o] != null && objs[o] != "")
				{
					obj[o] = new Array();
					for (var i = 0 ; i < 4 ; i++)
					{
						obj[o][i] = parseInt(objs[o][i]);
					}
					obj[o][2] = obj[o][2] - start_x;
					obj[o][3] = obj[o][3] - start_y; 
				}
				else
					obj[o] = null;
			}
			return (obj);
		}

		function	correctOrder(id, z)
		{
			var	new_array = new Array();
			var	n = 0;

			for (n = 0; n < objs.length; n++)
			{
				if (objs[n] != null && objs[n] != "")
				{
					if (z > objs[n][4])
					{
						console.log("push less " + n);
						new_array.push(n);
					}
				}
			}

			for (n = objs.length - 1; n >= 0; n--)
			{
				if (objs[n] != null && objs[n] != "")
				{
					if (z == objs[n][4])
					{
						console.log("push eq " + n);
						new_array.push(n);
					}
				}
			}

			for (n = 0; n < objs.length; n++)
			{
				if (objs[n] != null && objs[n] != "")
				{
					if (z < objs[n][4])
					{
						console.log("push gr " + n);
						new_array.push(n);
					}
				}
			}
			order = new_array;
		}

		function	appendOrder(id)
		{
			if (!order)
				order = new Array(id);
			else
				order.push(id);
		}

		function	deleteOrder(id)
		{
			if (order)
			{
				for (var i = 0; i < order.length; i++)
				{
					if (order[i] == id)
					{
						order[i] = null;
						break ;
					}
				}
			}
		}
