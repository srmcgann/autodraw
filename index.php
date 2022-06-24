<!DOCTYPE html>
<html>
  <head>
    <style>
    body, html{
      margin: 0;
      width: 100%;
      height: 100vh;
      overflow: hidden;
      background: #000;
    }
    canvas{
      border: 2px solid #486;
      position: absolute;
      top: 50%;
      left: 50%;
      background: url(./1HZmm6.png);
      background-color: #222;
      background-position: center center;
      background-size: 100% 100%;
      transform: translate(-50%, -50%);
    }
    .downloadLink{
      text-decoration: none;
      font-family: courier;
      font-size: 24px;
      color: #044;
      text-shadow: 1px 1px 1px #000;
      margin: 50px;
      padding: 10px;
      background: #0fa;
      display: inline-block;
      border-radius: 10px;
    }
    .newDiv{
      text-align: center;
    }
    .imgThumb{
      width: calc(100% - 50px);
      margin:25px;
    }
    #button{
      position:absolute;
      left:50%;
      top:50%;
      transform: translate(-50%,-50%);
      font-size: 24px;
    }
		#hotkeys{
      font-family: courier;
			color: #fff;
			font-size: 16px;
			text-shadow: 1px 1px 1px #000;
			background: #206;
			cursor: pointer;
			border: none;
			border-radius: 5px;
      top: 5px;
			left: 5px;
			position: fixed;
			z-index: 20;
    }
	</style>
  </head>
  <body>
	  <button id="hotkeys" onclick="showHotkeys()">hotkeys</button>
    <button onclick="Draw()" id="button">Draw</button>
    <div style="font-family: alice;position:absolute; z-index:-100">a</div>
    <canvas id="c"></canvas>
  </body>
  <script>
    showHotkeys=()=>{
      alert("W/A/S/D - movement\nARROW KEYS - look\nSHIFT - increased speed\nF - toggle floor, V - toggle viewfinder\nG - toggle background\n\n  TRANSFORM  MODES\nM - move\nR - rotate\nL - scale/size\n\n  SELECT MODES\n1 - vertex\n2 - face\n3 - shape\n\n  MODIFIERS\nshift + rotate (mouse x)  = roll\nctrl + up/down arrows = move camera vertically\n\n  SPECIAL KEYS\nY - toggle \"y-lock\"\nshift + camera tool (mouse y) vertical movement\nshift + move shape = duplicate")
    }
    document.fonts.onloadingdone=()=>{console.log('fonts loaded')}
    duration = 1000*240|0
    initialDelay = 3000
    vars=0
    song = './epiphany.mp3'
    vid = ''
    stage2 = false
    export_base = false
    fftSize = 128
    visualization = 13
    autoStart = 1

    recordingStarted=playing=vidplaying=analyzerSetup=0
    canvas2 = document.querySelector("#c")
    canvas2.width = 1920|0
    canvas2.height = 1080|0
    x2 = canvas2.getContext('2d')
    c = canvas2.cloneNode()
    c.id = 'canvas2'
    c.style.visibility = 'hidden'
    c.style.position = 'absolute'
    document.body.appendChild(c)
    c.width=1920
    c.height=1080
    c.style.width='calc(' + 1920 + 'px - 10px)'
    c.style.height='calc(' + 1080 + 'px - 10px)'


    //c.style.display='none'
    x = c.getContext('2d')
    C = Math.cos
    S = Math.sin
    t = 0
    T = Math.tan

    rsz=window.onresize=()=>{
      setTimeout(()=>{
        if(document.body.clientWidth > document.body.clientHeight*1.77777778+1|0){
          c.style.height = 'calc(100vh - 40px)'
          setTimeout(()=>c.style.width = 'calc(' + (c.clientHeight*1.77777778+1|0)+'px)',0)
        }else{
          c.style.width = 'calc(100vw - 40px)'
          setTimeout(()=>c.style.height = 'calc(' + (c.clientWidth/1.77777778+1|0)+'px)',0)
        }
        c.width=1920
        c.height=c.width/1.777777778+1|0
        if(document.body.clientWidth > document.body.clientHeight*1.77777778+1|0){
          canvas2.style.height = 'calc(100vh - 40px)'
          setTimeout(()=>canvas2.style.width = 'calc(' + (canvas2.clientHeight*1.77777778+1|0)+'px)',0)
        }else{
          canvas2.style.width = 'calc(100vw - 40px)'
          setTimeout(()=>canvas2.style.height = 'calc(' + (canvas2.clientWidth/1.77777778+1|0)+'px)',0)
        }
        canvas2.width=c.width
        canvas2.height=canvas2.width/1.777777778+1|0
      },0)
      redraw=true
    }
    rsz()

    async function Draw(){

      if(!t){
        R=(Rl,Pt,Yw,m)=>{X=S(p=(A=(M=Math).atan2)(X,Y)+Rl)*(d=(H=M.hypot)(X,Y)),Y=C(p)*d,Y=S(p=A(Y,Z)+Pt)*(d=H(Y,Z)),Z=C(p)*d,X=S(p=A(X,Z)+Yw)*(d=H(X,Z)),Z=C(p)*d;if(m)X+=oX,Y+=oY,Z+=oZ}
        I=(A,B,M,D,E,F,G,H)=>(K=((G-E)*(B-F)-(H-F)*(A-E))/(J=(H-F)*(M-A)-(G-E)*(D-B)))>=0&&K<=1&&(L=((M-A)*(B-F)-(D-B)*(A-E))/J)>=0&&L<=1?[A+K*(M-A),B+K*(D-B)]:0
        Q=q=>[c.width/2+X/Z*1e3,c.height/2+Y/Z*1e3]
        Rn=Math.random
        document.querySelector('#button').style.display='none'
        x.lineJoin=x.lineCap='butt'
      }

      if(stage2){
        if(song){
          analyzerSetup||setupAnalyzerAndContent()
        }else{
          loaded=1
        }
      } else { //base animation (pre freq)
            
        if(!t){
          cloneShapeQueue=[] 
          shiftCloneComplete=false
          history=[]
          editable = false
          allSelected = false
          cct=0
          draggingShapes=[]
          freshClick=true
          greyButtonImg = new Image()
          greyButtonImg.src = '11UEjG.png'
          greenButtonImg = new Image()
          greenButtonImg.src = 'h4Bwh.png'
          redButtonImg = new Image()
          redButtonImg.src = '2dNSSS.png'
          fullScreenButtonImg = new Image()
          fullScreenButtonImg.src = '1ERPZC.png'
          checkImg = new Image()
          checkImg.src = 'h79WY.png'
          

          async function loadReducedOBJ(url, scale, tx, ty, tz, rl, pt, yw, reduce) {
            let res
            await fetch(url+'?reduce='+reduce, res => res).then(data=>data.text()).then(data=>{
              res=JSON.parse(data)
              ax=ay=az=ct=0
              res.map(v=>{
                v.map(q=>{
                  q[1]*=-1
                  q[0]*=scale
                  q[1]*=scale
                  q[2]*=scale
                  ax+=q[0]
                  ay+=q[1]
                  az+=q[2]
                  ct++
                })
              })
              ax/=ct
              ay/=ct
              az/=ct
              res.map(v=>{
                v.map(q=>{
                  X=q[0]-ax
                  Y=q[1]-ay
                  Z=q[2]-az
                  R2(rl,pt,yw,0)
                  q[0]=X+tx
                  q[1]=Y+ty
                  q[2]=Z+tz
                })
              })
            })
            return res
          }
          
          async function loadOBJ(url, scale, tx, ty, tz, rl, pt, yw) {
            let res
            await fetch(url, res => res).then(data=>data.text()).then(data=>{
              a=[]
              data.split("\nv ").map(v=>{
                a=[...a, v.split("\n")[0]]
              })
              a=a.filter((v,i)=>i).map(v=>[...v.split(' ').map(n=>(+n.replace("\n", '')))])
              ax=ay=az=0
              a.map(v=>{
                v[1]*=-1
                ax+=v[0]
                ay+=v[1]
                az+=v[2]
              })
              ax/=a.length
              ay/=a.length
              az/=a.length
              a.map(v=>{
                X=(v[0]-ax)*scale
                Y=(v[1]-ay)*scale
                Z=(v[2]-az)*scale
                R2(rl,pt,yw,0)
                v[0]=X
                v[1]=Y
                v[2]=Z
              })
              maxY=-6e6
              a.map(v=>{
                if(v[1]>maxY)maxY=v[1]
              })
              a.map(v=>{
                v[1]-=maxY-oY
                v[0]+=tx
                v[1]+=ty
                v[2]+=tz
              })

              b=[]
              data.split("\nf ").map(v=>{
                b=[...b, v.split("\n")[0]]
              })
              b.shift()
              b=b.map(v=>v.split(' '))
              b=b.map(v=>{
                v=v.map(q=>{
                  return +q.split('/')[0]
                })
                v=v.filter(q=>q)
                return v
              })
              
              res=[]
              b.map(v=>{
                e=[]
                v.map(q=>{
                  e=[...e, a[q-1]]
                })
                e = e.filter(q=>q)
                res=[...res, e]
              })
            })
            return res
          }

          tools={
            defaultCursor: 'crosshair',
            show: true,
            hover: false,
            marq:{
              x: -1,
              y: -1,
              w: 0,
              h: 0
            },
            menu:{
              boundingRect: [],
              toggled: false,
              visible: false,
              hover: false,
              toggleButton: [],
              fontSize: 20,
              x: c.width-50-100-100,
              y: 140,
              options: [],
              items:[],
              optionsMode:{
                vertex: [
                  /*
                  {
                    name: 'ACTIONS -',
                    type: 'text',
                    height: 0
                  },
                  {
                    name: '',
                    type: 'text',
                    height: 1
                  },
                  {
                    name:'detach vertex',
                    type: 'checkbox',
                    callback: 'deleteVertex',
                    value: false
                  }
                  */
                ],
                face: [
                  /*
                  {
                    name: 'ACTIONS -',
                    type: 'text',
                    height: 0
                  },
                  */
                  {
                    name: '',
                    type: 'text',
                    height: 1
                  },
                  {
                    name:'DETACH FACE',
                    type: 'text',
                    height: -.15
                  },
                  {
                    name:'DETACH',
                    type: 'toggle-button',
                    category: 'detachMode',
                    color: '#fff',
                    value: true,
                    func: 'setFaceDetachMode'
                  },
                  {
                    name:'NO-DETACH',
                    type: 'toggle-button',
                    category: 'detachMode',
                    color: '#fff',
                    value: false,
                    func: 'setFaceDetachMode'
                  },
                ],
                shape: [
                  /*
                  {
                    name: 'ACTIONS -',
                    type: 'text',
                    height: 0
                  },
                  */
                  {
                    name: 'ACTIONS',
                    type: 'text',
                    height: -.2
                  },
                  {
                    name:'delete shape',
                    type: 'button',
                    color: '#faa',
                    buttonType: redButtonImg,
                    func: 'deleteShape'
                  },
                  {
                    name:'duplicate shape',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'duplicateShape'
                  },
                  {
                    name:'align to floor',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'alignToFloor'
                  },
                  {
                    name: 'TRANSFORM MODE',
                    type: 'text',
                    height: 0
                  },
                  {
                    name:'move   [M]',
                    type: 'toggle-button',
                    color: '#fff',
                    category: 'transformMode',
                    value: 'move',
                    func: 'setTransformMode'
                  },
                  {
                    name:'rotate [R]',
                    type: 'toggle-button',
                    color: '#fff',
                    category: 'transformMode',
                    value: 'rotate',
                    func: 'setTransformMode'
                  },
                  {
                    name:'scale  [L]',
                    type: 'toggle-button',
                    color: '#fff',
                    category: 'transformMode',
                    value: 'scale',
                    func: 'setTransformMode'
                  },
                  {
                    name: '',
                    type: 'text',
                    height: 0
                  },
                ],
                allMenus: [
                  {
                    name: '',
                    type: 'text',
                    height: 0
                  },
                  {
                    name: 'SELECTION MODE',
                    type: 'text',
                    height: -.15
                  },
                  {
                    name:'VERTEX [1]',
                    type: 'toggle-button',
                    category: 'selectMode',
                    color: '#fff',
                    value: 'vertex',
                    func: 'setSelectionMode'
                  },
                  {
                    name:'FACE   [2]',
                    type: 'toggle-button',
                    category: 'selectMode',
                    color: '#fff',
                    value: 'face',
                    func: 'setSelectionMode'
                  },
                  {
                    name:'SHAPE  [3]',
                    type: 'toggle-button',
                    category: 'selectMode',
                    color: '#fff',
                    value: 'shape',
                    func: 'setSelectionMode'
                  },
                ],
                'default': [
                  /*
                  {
                    name: 'ACTIONS -',
                    type: 'text',
                    height: 0
                  },
                  */
                  {
                    name: 'CREATE SHAPE',
                    type: 'text',
                    color: '#aff',
                    height: -.25,
                  },
                  {
                    name: 'tetrahedron',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 0
                  },
                  {
                    name: 'cube',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 1
                  },
                  {
                    name: 'octahedron',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 2
                  },
                  {
                    name: 'dodecahedron',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 3
                  },
                  {
                    name: 'icosahedron',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 4
                  },
                  {
                    name: 'sphere',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 5
                  },
                  {
                    name: 'chair',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 6
                  },
                  {
                    name: 'tiger',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 7
                  },
                  {
                    name: 'fish',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 8
                  },
                  {
                    name: 'house 1',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 9
                  },
                  {
                    name: 'house 2',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 18
                  },
                  //{
                  //  name: 'reduced fish',
                  //  type: 'button',
                  //  color: '#aff',
                  //  buttonType: greenButtonImg,
                  //  func: 'createShape',
                  //  shapeChoice: 17
                  //},
                  {
                    name: 'axe',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 14
                  },
                  {
                    name: 'pickaxe',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 15
                  },
                  {
                    name: 'tree 1',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 16
                  },
                  {
                    name: 'tree 2',
                    type: 'button',
                    color: '#aff',
                    buttonType: greenButtonImg,
                    func: 'createShape',
                    shapeChoice: 19
                  },
                  //{
                  //  name: 'ladybug',
                  //  type: 'button',
                  //  color: '#aff',
                  //  buttonType: greenButtonImg,
                  //  func: 'createShape',
                  //  shapeChoice: 20
                  //},
                  //{
                  //  name: 'reduced ladybug',
                  //  type: 'button',
                  //  color: '#aff',
                  //  buttonType: greenButtonImg,
                  //  func: 'createShape',
                  //  shapeChoice: 21
                  //},
                ]
              }
            },
            transformMode: 'move',
            selectMode: 'shape',
            showFloor: true,
            showGlobalSphere: true,
            detachFace: false,
            viewfinder:{
              visible: true,
              img: (function(){let i=(new Image());i.src='1STcyi.png';return i})(),
              alpha: .2
            },
            editMode: 'move',
            fly: {
              enabled: false
            },
            gyro:{
              hover: false,
              x: c.width/2-50-10,
              y: 50+10
            },
            move:{
              hover: false,
              x: c.width-10-100-100,
              y: 50+10
            },
            info:{
              toggled: false,
              visible: false,
              hover: false,
              toggleButton: [],
              fontSize: 20,
              x: c.width-50-100-100,
              y: 140
            }
          }
          
          draggingShape=false
          
          showIcon = new Image()
          showIcon.src = '8lHEh.png'
          hideIcon = new Image()
          hideIcon.src = 'YUlip.png'
          
          duplicateShape=(newshp, clonePos)=>{
            newshp=JSON.parse(JSON.stringify(newshp))
            newshp[6]=newshp[6].filter((q,j)=>j<newshp[6].length-1)
            newshp[6]=newshp[6].map(v=>{
              v=v.filter((q,j)=>j<v.length-1)
              v=v.map((q,j)=>{
                q=q.filter((n,m)=>m<q.length-1)
                return q
              })
              return v
            })
            let tx,ty,tz,rl,pt,yw
            if(clonePos){
              tx=newshp[0]
              ty=newshp[1]
              tz=newshp[2]
              rl=newshp[3]
              pt=newshp[4]
              yw=newshp[5]
            }else {
              tx=-oX-S(p=Yw)*C(q=Pt)*10+1
              ty=-oY-S(q)*10
              tz=-oZ+C(p)*C(q)*10
              rl=0,pt=0,yw=0
            }
            cloneShapeQueue=[...cloneShapeQueue, [newshp[6], tx,ty,tz,rl,pt,yw,'#0643',newshp[9],newshp[10],newshp[11]]]
          }


          doAction=v=>{
            if(tools.menu.visible){
              func=v[0].func
              switch(func){
                case 'setFaceDetachMode':
                  tools.detachFace = v[0].value
                  closeMenus()
                break
                case 'setSelectionMode':
                  tools.selectMode = v[0].value
                  if(tools.selectMode !== 'shape') tools.transformMode = 'move'
                  //closeMenus()
                  tools.menu.items=[]
                  tools.menu.options=[]
                  rw=0
                  tools.menu.options.map(v=>{
                    renderMenuItem(tools.menu.x,tools.menu.y+tools.menu.fontSize*(rw+1), v, rw++)
                  })
                break
                case 'setTransformMode':
                  tools.transformMode = v[0].value
                  tools.menu.items=[]
                  tools.menu.options=[]
                  rw=0
                  tools.menu.options.map(v=>{
                    renderMenuItem(tools.menu.x,tools.menu.y+tools.menu.fontSize*(rw+1), v, rw++)
                  })
                  closeMenus()
                break
                case 'createShape':
                  tx=-oX-S(p=Yw)*C(q=Pt)*10
                  ty=-oY-S(q)*10
                  tz=-oZ+C(p)*C(q)*10

                  pushShape(base_shapes[v[0].shapeChoice].shape, tx,ty,tz,0,-q,-p,base_shapes[v[0].shapeChoice].color,base_shapes[v[0].shapeChoice].wireframe,base_shapes[v[0].shapeChoice].useFill, base_shapes[v[0].shapeChoice].gyro) // user-relative -q,-p)
                  draggingShapes=[]
                  closeMenus()
                break
                case 'deleteShape':
                  shps=shps.filter(v=>{
                    return !(v[v.length-1] || v[6].filter(q=>{
                      return q[q.length-1] || (q.length && q.filter(n=>n[3]).length)
                    }).length)
                  })
                  draggingShapes=[]
                  closeMenus()
                break
                case 'alignToFloor':
                  maxY=-6e6;
                  (l=shps.filter(v=>{
                    let sel = false
                    sel = v[v.length-1]
                    if(!sel){
                      v[6].map(q=>{
                        if(q[q.length-1]) sel = true
                        if(q.length && !sel){
                          q.map(n=>{
                            if(n[3]) sel = true
                          })
                        }
                      })
                    }
                    return sel
                  })).map(v=>{
                    v[6].map((q,j)=>{
                      if(q.length){
                        q.map(n=>{
                          X=n[0]
                          Y=n[1]
                          Z=n[2]
                          //R2(v[3],v[4],v[5],0)
                          if(Y>maxY){
                            maxY=Y
                          }
                        })
                      }
                    })
                  })
                  l.map(v=>{
                    v[1]=-maxY
                  })
                  //draggingShapes=[]
                  closeMenus()
                break
                case 'duplicateShape':
                  cloneShapeQueue=[]
                  shps.map(v=>{
                    if(v[v.length-1] || v[6].filter(q=>{
                      return q.length && q[length-1]
                    }).length){
                      let newShp = JSON.parse(JSON.stringify(v))
                      newShp[6].map(q=>{
                        if(q.length){
                          q.map(n=>{
                            n[3]=false
                          })
                          q[length-1]=false
                        }
                      })
                      duplicateShape(newShp,0)
                    }
                  })
                  draggingShapes=[]
                  closeMenus()
                break
              }
            }
          }
          
          selectedShapes=()=>{
            return shps.filter(v=>{
              return v[v.length-1] || v[6].filter(q=>{
                return q[q.length-1] || (q.length && q.filter(n=>n[3]).length)
              }).length
            })
          }
          
          renderMenuItem=(tx, ty, v, rw)=>{
            if(tools.menu.visible){
              switch(v.type){
                case 'text':
                  x.fillStyle='#000'
                  x.strokeStyle='#fff'
                  rw+=v.height
                  printLn(`${v.name}`, rw*2.2, 0, 'menu')
                break
                case 'checkbox':
                  printLn(`${v.name}`, ++rw, 2, 'menu')                
                  x.fillStyle='#555'
                  x.fillRect(...(a=[tx-2.5,ty-52.5+25*rw,30,30]))
                  if(v.value){
                    x.drawImage(checkImg,tx,ty-50+25*rw,25,25)
                  }
                break;
                case 'toggle-button':
                  if(v.buttonType){
                    bimg = v.buttonType
                  } else {
                    switch(v.category){
                      case 'transformMode':
                        bimg = tools.transformMode == v.value ? greenButtonImg : greyButtonImg
                      break
                      case 'selectMode':
                        bimg = tools.selectMode == v.value ? greenButtonImg : greyButtonImg
                      break
                      case 'detachMode':
                        bimg = tools.detachFace == v.value ? greenButtonImg : greyButtonImg
                      break
                    }
                  }
                  x.drawImage(bimg, ...(a=[tx,ty-50+25*rw,285,35]))
                  x.strokeStyle='#000c'
                  x.lineWidth=5
                  x.strokeText(`${v.name}`,tx+15,ty-25+25*rw-2)
                  x.strokeStyle=v.color
                  x.lineWidth=2
                  x.strokeText(`${v.name}`,tx+15,ty-25+25*rw-2)
                  x.fillStyle=v.color
                  x.fillText(`${v.name}`,tx+15,ty-25+25*rw-2)
                  tools.menu.items.push([v, a])
                break;
                case 'button':
                  bimg = v.buttonType
                  x.drawImage(bimg, ...(a=[tx,ty-50+25*rw,285,35]))
                  x.strokeStyle='#000c'
                  x.lineWidth=5
                  x.strokeText(`${v.name}`,tx+15,ty-25+25*rw-2)
                  x.strokeStyle=v.color
                  x.lineWidth=2
                  x.strokeText(`${v.name}`,tx+15,ty-25+25*rw-2)
                  x.fillStyle=v.color
                  x.fillText(`${v.name}`,tx+15,ty-25+25*rw-2)
                  tools.menu.items.push([v, a])
                break;
              }
            }
          }

          closeMenus=()=>{
            tools.menu.options=[]
            tools.menu.items=[]
            tools.menu.visible=false
          }

          doMove=(v)=>{
            if(keys[16] && !shiftCloneComplete){
              duplicateShape(v, 1)
            }
            tools.marq.x=-1
            tools.marq.y=-1
            tools.marq.w=tools.marq.h=0
            v[0]+=S(p=-Yw+Math.PI/2)*d1*C(Pt)-S(Pt)*(S(q=-Yw+Math.PI/2)*d1+C(q)*d2)*-S(Pt)
            v[1]+=C(Pt)*d2
            v[2]+=C(p)*d1*C(Pt)+S(Pt)*(S(q)*d2-C(q)*d1)
          }
          
          doRotation=v=>{
            td1=d1,td2=d2
            tools.marq.x=-1
            tools.marq.y=-1
            tools.marq.w=tools.marq.h=0
            if(cct){
              if(!gotRotCenters){
                console.log(1)
                gotRotCenters=true
                rcx=rcy=rcz=0;
                (l=shps.filter((v,i)=>i<shps.length-1&&v[v.length-1])).map(v=>{
                  rcx+=v[0]
                  rcy+=v[1]
                  rcz+=v[2]
                })
                rcx/=l.length
                rcy/=l.length
                rcz/=l.length;
              }
              (l=shps.filter((v,i)=>i<shps.length-1&&v[v.length-1])).map(v=>{
                X=v[0]-rcx
                Y=v[1]-rcy
                Z=v[2]-rcz
                R(Rl,Pt,Yw,0)
                if(keys[16]){
                  R2(-td1/10/(l.length+1), -td2/10/(l.length+1), 0, 0)
                }else{
                  R2(0,-td2/10/(l.length+1),-td1/10/(l.length+1),0)
                }
                R(-Rl,-Pt,-Yw,0)
                v[0]=rcx+X
                v[1]=rcy+Y
                v[2]=rcz+Z
              })
            }
            
            v[6].map(q=>{
              if(q.length){
                q.map(n=>{
                  X=n[0]
                  Y=n[1]
                  Z=n[2]
                  R(Rl,Pt,Yw,0)
                  if(keys[16]){
                    R2(-td1/10, -td2/10, 0, 0)
                  }else{
                    R2(0, -td2/10, -td1/10, 0)
                  }
                  R(-Rl,-Pt,-Yw,0)
                  n[0]=X
                  n[1]=Y
                  n[2]=Z
                })
              }
            })

            if(typeof v[11] !== 'undefined'){
              v[11].map(q=>{
                q.map(n=>{
                  X=n[0]
                  Y=n[1]
                  Z=n[2]
                  R(Rl,Pt,Yw,0)
                  if(keys[16]){
                    R2(-td1/10, -td2/10, 0, 0)
                  }else{
                    R2(0, -td2/10, -td1/10, 0)
                  }
                  R(-Rl,-Pt,-Yw,0)
                  n[0]=X
                  n[1]=Y
                  n[2]=Z
                })
              })
            }

            //v[5]+=-td1/20
            //v[4]+=-td2/20
          }
          
          doFaceRotation=v=>{
            tools.marq.x=-1
            tools.marq.y=-1
            tools.marq.w=tools.marq.h=0
            if(!gotRotCenters){
              gotRotCenters=true
              c_=rcx=rcy=rcz=0;
              (l=shps.filter((v,i)=>i<shps.length-1)).map(v=>{
                v[6].map(q=>{
                  if(q.length && (!tools.detachFace || !q.filter(v=>v.length&&!v[3]).length)){
                    q.map(n=>{
                      if(n[3]){
                        rcx+=n[0]
                        rcy+=n[1]
                        rcz+=n[2]
                        c_++
                      }
                    })
                  }
                })
              })
              rcx/=c_
              rcy/=c_
              rcz/=c_;
            };
            (l=shps.filter((v,i)=>i<shps.length-1)).map(v=>{
              v[6].map(q=>{
                if(q.length && (!tools.detachFace || !q.filter(v=>v.length&&!v[3]).length)){
                  q.map(n=>{
                    if(n[3]){
                      X=n[0]-rcx
                      Y=n[1]-rcy
                      Z=n[2]-rcz
                      R2(0,-d2/20,-d1/20,0)
                      n[0]=X+rcx
                      n[1]=Y+rcy
                      n[2]=Z+rcz
                    }
                  })
                }
              })
            })
          }

          doFaceMove=v=>{
            tools.marq.x=-1
            tools.marq.y=-1
            tools.marq.w=tools.marq.h=0;
            (l=shps.filter((v,i)=>i<shps.length-1)).map(v=>{
              v[6].map(q=>{
                if(q.length && (!tools.detachFace || !q.filter(v=>v.length&&!v[3]).length)){
                  q.map(n=>{
                    if(n[3]){
                      n[0]+=S(p=-Yw+Math.PI/2)*d1*C(Pt)-S(Pt)*(S(q=-Yw+Math.PI/2)*d1+C(q)*d2)*-S(Pt)
                      n[1]+=C(Pt)*d2
                      n[2]+=C(p)*d1*C(Pt)+S(Pt)*(S(q)*d2-C(q)*d1)
                    }
                  })
                }
              })
            })
          }

          doScale=v=>{
            tools.marq.x=-1
            tools.marq.y=-1
            tools.marq.w=tools.marq.h=0
            v[6].map(q=>{
              if(q.length){
                q.map(v=>{
                  if(v.length){
                    v[0]*=1-(d2/10)
                    v[1]*=1-(d2/10)
                    v[2]*=1-(d2/10)
                  }
                })
              }
            })
          }

          drawMenu=()=>{
            x.globalAlpha=1
            rw=0
            mbuttons.map(v=>false)
            tools.menu.visible=true
            mw=300
            mh=925
            x.strokeStyle='#8fc6'
            x.lineWidth = 1
            omx=Math.max(20, omx)
            omy=Math.max(20, omy)
            ox=Math.max(0,mw-(c.width-omx)+10)
            oy=Math.max(0,mh-(c.height-omy)+10)
            a=[omx-ox,omy-oy,mw,mh]
            x.strokeRect(a[0]-10,a[1]-10,a[2],a[3])
            tools.menu.x=a[0]
            tools.menu.y=a[1]
            x.strokeStyle='#fff3'
            x.fillStyle='#023c'
            //x.strokeRect(...(a=[tools.menu.x-10, tools.menu.y-10, 250, tools.menu.visible?940:46]))
            x.fillRect(...tools.menu.boundingRect=[a[0]-10,a[1]-10,a[2],a[3]])
            x.lineJoin=x.lineCap='butt'
            x.font=tools.menu.fontSize+'px courier'
            x.fillStyle='#8fc'
            
            x.globalAlpha=.6
            x.drawImage(tools.menu.visible?hideIcon:showIcon,...(tools.menu.toggleButton=[a[0]+a[2]-65,a[1]+3,40,40]))
            x.globalAlpha=1
            
            if(!tools.menu.options.length){
              if(draggingShape && draggingShapes.length){
                tools.menu.options=[
                  ...tools.menu.optionsMode.allMenus,
                  ...tools.menu.optionsMode[tools.selectMode],
                ]
              } else {
                tools.menu.options=[
                  ...tools.menu.optionsMode.allMenus,
                  ...tools.menu.optionsMode['default'],
                ]
              }
            }
            
            rw=0
            tools.menu.options.map(v=>{
              renderMenuItem(tools.menu.x,tools.menu.y+tools.menu.fontSize*(rw+1), v, rw++)
            })
          }
          
          processMouse=e=>{
            
            //if(!tools.menu.hover && !tools.info.hover) canvas2.style.cursor = tools.hover ? 'pointer' : tools.defaultCursor
            dmX=dmY=0
            if(e.movementX>0)dmX=(100+e.movementX)/10
            if(e.movementX<0)dmX=(-100+e.movementX)/10
            if(e.movementY>0)dmY=(100+e.movementY)/10
            if(e.movementY<0)dmY=(-100+e.movementY)/10
            if(mbuttons[0] && !freshClick && tools.menu.visible) return
            //if(!mbuttons[0] && typeof draggingShapes !== 'undefined' && draggingShapes.length && tools.marq.x!==-1){
            //  console.log(draggingShape)
            //  return
            //}
            if(editable && !tools.hover){// && ((!draggingShape || mbuttons[2] || mbuttons[0]) && !draggingShapes.length)){
              if(mbuttons[0] && !menuHover()){
                tools.marq.x=tools.marq.x==-1?mx:tools.marq.x
                tools.marq.y=tools.marq.y==-1?my:tools.marq.y
                tools.marq.w+=e.movementX
                tools.marq.h+=e.movementY
              }
              //if(!cct) draggingShapes=[]
            }
            
            if((tools.selectMode !=='shape' && !mbuttons[0]) || !cct && !tools.menu.visible && !tools.hover){
              draggingShape=false
              draggingShapes=[]
            }
            if((tools.marq.x==-1 || (cct && freshClick || draggingShape)) && (mbuttons[0] || mbuttons[2]) && editable && !menuHover()){
              d1=dmX*500/(1+100/((1+Z)**2))/8000
              d2=dmY*500/(1+100/((1+Z)**2))/8000
              let Z_=6e6
              if(tools.selectMode=='face'){
                switch(tools.transformMode){
                  case 'move':
                    doFaceMove()
                  break
                  case 'rotate':
                    doFaceRotation()
                  break
                  case 'scale':
                    //doScale(v)
                  break
                }
              }
              shps.map((v,i)=>{
                if(i<shps.length-1){
                  if(v[v.length-1]){
                    if(tools.selectMode=='shape'){
                      switch(tools.transformMode){
                        case 'move':
                          doMove(v)
                        break
                        case 'rotate':
                          doRotation(v)
                        break
                        case 'scale':
                          doScale(v)
                        break
                      }
                    }
                  }
                  X=v[0]
                  Y=v[1]
                  Z=v[2]
                  R(Rl,Pt,Yw,1)
                  switch(tools.selectMode){
                    case 'vertex':
                      v[6].map((n,m)=>{
                        if(n.length>1){
                          n.map(v=>{
                            if(v[v.length-1]){
                              draggingShape=true
                              draggingShapes=[...draggingShapes,[i,m]]
                              if(mbuttons[0]){
                                switch(tools.transformMode){
                                  case 'move':
                                    v[0]+=S(p=-Yw+Math.PI/2)*d1*C(Pt)-S(Pt)*(S(q=-Yw+Math.PI/2)*d1+C(q)*d2)*-S(Pt)
                                    v[1]+=C(Pt)*d2
                                    v[2]+=C(p)*d1*C(Pt)+S(Pt)*(S(q)*d2-C(q)*d1)
                                  break
                                  case 'rotate':
                                    //doRotation(v)
                                  break
                                  case 'scale':
                                    //doScale(v)
                                  break
                                }
                              }
                            }
                          })
                        }
                      })
                    break
                    case 'face':
                      if(cct){ // multiple shapes selected
                        v[6].map((n,m)=>{
                          if(n.length>1){
                            n.map(v=>{
                              if(!tools.detachFace){
                                if(v[v.length-1]){
                                  draggingShape=true
                                  draggingShapes=[...draggingShapes,[i,m]]
                                  if(mbuttons[0]){
                                    switch(tools.transformMode){
                                      case 'move':
                                        //v[0]+=S(p=-Yw+Math.PI/2)*d1*C(Pt)-S(Pt)*(S(q=-Yw+Math.PI/2)*d1+C(q)*d2)*-S(Pt)
                                        //v[1]+=C(Pt)*d2
                                        //v[2]+=C(p)*d1*C(Pt)+S(Pt)*(S(q)*d2-C(q)*d1)
                                      break
                                      case 'rotate':
                                        //console.log(2)
                                        //doFaceRotation(v)
                                      break
                                      case 'scale':
                                        //doScale(v)
                                      break
                                    }
                                  }
                                }
                              } else {
                                if(n[n.length-1]){
                                  draggingShape=true
                                  draggingShapes=[...draggingShapes,[i,m]]
                                  if(mbuttons[0]){
                                    switch(tools.transformMode){
                                      case 'move':
                                        v[0]+=S(p=-Yw+Math.PI/2)*d1*C(Pt)-S(Pt)*(S(q=-Yw+Math.PI/2)*d1+C(q)*d2)*-S(Pt)
                                        v[1]+=C(Pt)*d2
                                        v[2]+=C(p)*d1*C(Pt)+S(Pt)*(S(q)*d2-C(q)*d1)
                                      break
                                      case 'rotate':
                                        //doFaceRotation(v)
                                      break
                                      case 'scale':
                                        //doScale(v)
                                      break
                                    }
                                  }
                                }
                              }
                            })
                          }
                        })
                      } else {
                        v[6].map((n,m,a)=>{
                          if(n.length>1){
                            n.map((v,i)=>{
                              if(!tools.detachFace){
                                if(n[n.length-1]){
                                  draggingShape=true
                                  draggingShapes=[...draggingShapes,[i,m]]
                                  if(mbuttons[0]){
                                    switch(tools.transformMode){
                                      case 'move':
                                        lx=v[0]
                                        ly=v[1]
                                        lz=v[2]
                                        v[0]+=S(p=-Yw+Math.PI/2)*d1*C(Pt)-S(Pt)*(S(q=-Yw+Math.PI/2)*d1+C(q)*d2)*-S(Pt)
                                        v[1]+=C(Pt)*d2
                                        v[2]+=C(p)*d1*C(Pt)+S(Pt)*(S(q)*d2-C(q)*d1)
                                        a.map((q,j)=>{
                                          if(q.length){
                                            q.map((l,n)=>{
                                              if(l.length){
                                                if((d=H(l[0]-lx,l[1]-ly,l[2]-lz))<.1){
                                                  l[0]=v[0]
                                                  l[1]=v[1]
                                                  l[2]=v[2]
                                                }
                                              }
                                            })
                                          }
                                        })
                                      break
                                      case 'rotate':
                                        //doFaceRotation(v)
                                      break
                                      case 'scale':
                                        //doScale(v)
                                      break
                                    }
                                  }
                                }
                              } else {
                                if(n[n.length-1]){
                                  draggingShape=true
                                  draggingShapes=[...draggingShapes,[i,m]]
                                  if(mbuttons[0]){
                                    switch(tools.transformMode){
                                      case 'move':
                                        v[0]+=S(p=-Yw+Math.PI/2)*d1*C(Pt)-S(Pt)*(S(q=-Yw+Math.PI/2)*d1+C(q)*d2)*-S(Pt)
                                        v[1]+=C(Pt)*d2
                                        v[2]+=C(p)*d1*C(Pt)+S(Pt)*(S(q)*d2-C(q)*d1)
                                      break
                                      case 'rotate':
                                        //doFaceRotation(v)
                                      break
                                      case 'scale':
                                        //doScale(v)
                                      break
                                    }
                                  }
                                }
                              }
                            })
                          }
                        })
                      }
                    break
                    case 'shape':
                      if(Z>0&&Z<Z_&&v[6].filter(q=>q[q.length-1]).length){
                        draggingShape=true
                        draggingShapes=[...draggingShapes,[i]]
                        if(mbuttons[0]){
                          switch(tools.transformMode){
                            case 'move':
                              //v[0]+=S(p=-Yw+Math.PI/2)*d1*C(Pt)-S(Pt)*(S(q=-Yw+Math.PI/2)*d1+C(q)*d2)*-S(Pt)
                              //v[1]+=C(Pt)*d2
                              //v[2]+=C(p)*d1*C(Pt)+S(Pt)*(S(q)*d2-C(q)*d1)
                            break
                            case 'rotate':
                              //doRotation(v)
                            break
                            case 'scale':
                              //doScale(v)
                            break
                          }
                          Z_=Z
                        }
                      }
                    break
                  }
                }
              })
            }
            tools.gyro.hover=Math.hypot(mx-(tools.gyro.x+c.width/2),my-tools.gyro.y)<75
            tools.move.hover=Math.hypot(mx-tools.move.x,my-tools.move.y)<75
            tools.info.hover= mx>tools.info.toggleButton[0] && mx < tools.info.toggleButton[0]+tools.info.toggleButton[2] &&
               my>tools.info.toggleButton[1] && my < tools.info.toggleButton[1]+tools.info.toggleButton[3]
            tools.menu.hover= mx>tools.menu.toggleButton[0] && mx < tools.menu.toggleButton[0]+tools.menu.toggleButton[2] &&
               my>tools.menu.toggleButton[1] && my < tools.menu.toggleButton[1]+tools.menu.toggleButton[3]
            for (let [key, value] of Object.entries(tools)){
              if(mbuttons[0] && tools[key].hover){
                switch(key){
                  case 'menu':
                   if(!tools.menu.toggled){
                    tools.menu.visible = !tools.menu.visible
                    tools.menu.toggled = true
                   }
                  break
                  case 'info':
                   if(!tools.info.toggled){
                    tools.info.visible = !tools.info.visible
                    tools.info.toggled = true
                   }
                  break
                  case 'gyro':
                    if(!tools.menu.visible){
                      if(keys[16]){
                        Rl+=dmX/1000
                        Pt-=dmY/1000
                        Pt=Math.min(Math.PI/2, Math.max(-Math.PI/2,Pt))
                      }else{
                        Yw-=dmX/1000
                        Pt-=dmY/1000
                        Pt=Math.min(Math.PI/2, Math.max(-Math.PI/2,Pt))
                      }
                    }
                  break;
                  case 'move':
                    if(!tools.menu.visible){
                      d1=dmX/100
                      d2=dmY/100
                      if(keys[16]){
                        oX-=S(p=-Yw+Math.PI/2)*d1
                        oY-=d2
                        oZ-=C(p)*d1
                      } else {
                        oX-=S(p=-Yw+Math.PI/2)*d1-S(q=-Yw)*d2
                        oY-=0
                        oZ-=C(p)*d1-C(q)*d2
                      }
                    }
                  break;
                }
              }
            }
            
            //document.body.style.cursor = editable ? 'pointer' : tools.defaultCursor
            document.body.style.cursor = tools.hover ? 'pointer' : tools.defaultCursor
            tools.menu.items.map(v=>{
              if(mx>v[1][0] && mx<v[1][0]+v[1][2] &&  my>v[1][1]&&my<v[1][1]+v[1][3]){
                document.body.style.cursor='pointer'
                if(mbuttons[0] && v[0].func){
                  doAction(v)
                }
              }
            })
            if(mbuttons[0]){
              if(!menuHover()){
                closeMenus()
              }
            }
            if(tools.menu.visible){
              draggingShape=true
            }
            if(!cct && !draggingShape && freshClick && !draggingShapes.length){
              tools.marq.x=-1
              tools.marq.y=-1
              tools.marq.w=tools.marq.h=0
            }
          }
          
          menuHover=()=>{
            return tools.menu.visible && !(mx<tools.menu.boundingRect[0] || my<tools.menu.boundingRect[1] ||
               mx>tools.menu.boundingRect[0]+tools.menu.boundingRect[2] ||
               my>tools.menu.boundingRect[1]+tools.menu.boundingRect[3])
          }

          processKeys=e=>{
            switch(e.keyCode){
              case 46:
                switch(tools.selectMode){
                  case 'vertex':
                    shps=shps.map(v=>{
                      v[6]=v[6].filter(q=>{
                        keepPoly=true
                        if(q.length){
                          keepPoly=!q.filter(n=>{
                            return n[3]
                          }).length
                        }
                        return keepPoly
                      })
                      return v
                    })
                  break
                  case 'face':
                    shps=shps.map(v=>{
                      v[6]=v[6].filter(q=>{
                        return !q.length || !q.filter(n=>n[n.length-1]).length
                      })
                      return v
                    })
                  break
                  case 'shape':
                    shps=shps.filter((v,i)=>!v[v.length-1])
                  break
                }
                draggingShapes=[]
              break;
              case 27:
                tools.marq.x=tools.marq.y=-1
                tools.marq.w=tools.marq.h=0
                if(tools.menu.visible) closeMenus()
              break
              case 116:
                window.location.reload()
              break
              case 49:
                tools.selectMode='vertex'
              break
              case 50:
                tools.selectMode='face'
              break
              case 71:
                tools.showGlobalSphere=!tools.showGlobalSphere
              break
              case 51:
                tools.selectMode='shape'
              break
              case 84:
                tools.show = !tools.show
              break
              case 70:
                tools.showFloor=!tools.showFloor
              break
              case 89:
                tools.fly.enabled = !tools.fly.enabled
              break
              case 86:
                tools.viewfinder.visible = !tools.viewfinder.visible
              break
            }
            if(tools.selectMode !== 'shape') tools.transformMode = 'move'
          }

          moveIcon = new Image()
          moveIcon.src = './1jIQ7v.png'
          showCursorDot=false
          mx=c.width-10
          my=10
          mdx=mdy=0
          mbuttons=Array(3).fill(false)
          keys=Array(256).fill(false)
          window.onkeydown=e=>{
            e.stopPropagation()
            e.preventDefault()
            keys[e.keyCode]=true
            processKeys(e)
          }
          window.onkeyup=e=>{
            keys[e.keyCode]=false
            if(e.keyCode==68) shiftCloneComplete=false
          }
          window.onwheel=e=>{
            if(!tools.menu.visible){
              oXv+=S(p=-Yw)*C(q=Pt)*e.deltaY/300
              if(tools.fly.enabled) oYv-=S(q)*e.deltaY/300
              oZv+=C(p)*C(q)*e.deltaY/300
            }
          }
          window.onmousemove=e=>{
            if(!mbuttons[0] && !mbuttons[2]){
              let rect = c.getBoundingClientRect()
              mx=(e.x-(rect.left+2))/(c.clientWidth)*c.width
              my=(e.y-(rect.top+2))/(c.clientHeight)*c.height
            }

            if(!shiftCloneComplete && cloneShapeQueue.length){
              cloneShapeQueue.map(v=>{
                pushShape(...v)
              })
              cloneShapeQueue=[] 
              shiftCloneComplete=true
            }

            processMouse(e)
          }
          
          c.onmousedown = c.onmouseup = canvas2.onmousedown = canvas2.onmouseup = e => {
            //e.stopPropagation()
            e.preventDefault()
            return null
          }
          
          deselectAll=()=>{
            ct=cct=0
            shps.map((v,i)=>{
              if(i<shps.length-1){
                v[v.length-1]=false
                v[6].map((q,j)=>{
                  if(j<v[6].length-1){
                    q[q.length-1]=false
                    if(j<v[6].length){
                      q.map(n=>{
                        n[3]=false
                      })
                    }
                  }
                })
              }
            })
            //freshClick=true
            allSelected = false
            tools.marq.x=-1
            tools.marq.y=-1
            tools.marq.w=tools.marq.h=0
            draggingShape=false
            draggingShapes=[]
          }
          
          canvas2.onmousedown=e=>{
            if(tools.transformMode=='rotate')gGyro=defaultGyro()
            e.preventDefault()
            e.stopPropagation()
            c.requestPointerLock()
            let rect = c.getBoundingClientRect()
            mx=(e.x-(rect.left+2))/(c.clientWidth)*c.width
            my=(e.y-(rect.top+2))/(c.clientHeight)*c.height
            mdx=mx
            mdy=my
            freshClick=true
            mbuttons[e.button]=true
            if(keys[16] && mbuttons[0]) shiftCloneComplete=false

            if(mbuttons[0] && tools.transformMode == 'rotate'){
              gGyro = defaultGyro()
            }

            if(mbuttons[2]){
              if(tools.menu.visible){
                draggingShape=false
                draggingShapes=[]
                closeMenus()
                omx=mx
                omy=my
                //processMouse(e)
              }
              omx=mx
              omy=my
            }
              if(e.button==0 && !tools.menu.visible && !tools.hover && !menuHover() && !draggingShape) deselectAll()
              //checkHover()
              processMouse(e)
          }
          window.onmouseup=e=>{
            d1=d2=td1=td2=0
            if(tools.transformMode=='rotate')gGyro=defaultGyro()
            shiftCloneComplete = false
            e.preventDefault()
            e.stopPropagation()
            gotRotCenters=false
            mbuttons[e.button]=false
            document.exitPointerLock()
            tools.info.toggled=false
            tools.menu.toggled=false
            //setTimeout(()=>{
              if(Math.abs(tools.marq.w)<3 && Math.abs(tools.marq.h)<3) deselectAll()
            //},0)
          }
          R=(Rl,Pt,Yw,o)=>{
            if(o)X+=oX,Y+=oY,Z+=oZ
            A=(M=Math).atan2
            H=M.hypot
            X=S(p=A(X,Z)+Yw)*(d=H(X,Z)),Z=C(p)*d
            X=S(p=A(X,Y)+Rl)*(d=H(X,Y)),Y=C(p)*d
            Y=S(p=A(Y,Z)+Pt)*(d=H(Y,Z)),Z=C(p)*d
          }
          R2=(Rl,Pt,Yw,m)=>{X=S(p=(A=(M=Math).atan2)(X,Y)+Rl)*(d=(H=M.hypot)(X,Y)),Y=C(p)*d,Y=S(p=A(Y,Z)+Pt)*(d=H(Y,Z)),Z=C(p)*d,X=S(p=A(X,Z)+Yw)*(d=H(X,Z)),Z=C(p)*d;if(m)X+=oX,Y+=oY,Z+=oZ}
          printLn=(s,ln,indent,dest)=>{
            x.fillText(s, tools[dest].x+(tools[dest].fontSize*indent), tools[dest].y+tools[dest].fontSize*(ln+1))
            x.strokeText(s, tools[dest].x+(tools[dest].fontSize*indent), tools[dest].y+tools[dest].fontSize*(ln+1))
          }
          DrawSolid=(dod, sx, sy, sz, rl, pt, yw, strokeStyle, fillStyle, useFill, globalAlpha, editable, wireframe) => {
            dod.sort((a,b)=>{
              if(a.length&&b.length){
                ax=zy=az=0
                a.filter(q=>q.length).map(q=>{
                  X=q[0]
                  Y=q[1]
                  Z=q[2]
                  R2(rl,pt,yw,0)
                  ax+=X+sx
                  ay+=Y+sy
                  az+=Z+sz
                })
                X=ax/=a.length-1
                Y=ay/=a.length-1
                Z=az/=a.length-1
                R(Rl,Pt,Yw,1)
                Z1=Z
                bx=by=bz=0
                b.filter(q=>q.length).map(q=>{
                  X=q[0]
                  Y=q[1]
                  Z=q[2]
                  //R2(rl,pt,yw,0)
                  bx+=X+sx
                  by+=Y+sy
                  bz+=Z+sz
                })
                X=bx/=b.length-1
                Y=by/=b.length-1
                Z=bz/=b.length-1
                R(Rl,Pt,Yw,1)
                Z2=Z
                return Z2-Z1
              }
            })
            dod.map((v,i)=>{
              if(v.length){
                if(wireframe) x.beginPath()
                if(editable) a=[]
                v.map((q,j)=>{
                  if(q.length){
                    X=q[0]
                    Y=q[1]
                    Z=q[2]
                    //R2(rl,pt,yw,0)
                    X+=sx
                    Y+=sy
                    Z+=sz
                    R(Rl,Pt,Yw,1)
                    let l=[...Q(), Z, j, i]
                    if(editable){
                      a = [...a, l]
                    }
                    if(Z>0 && wireframe) x.lineTo(...l)
                  }
                })
                if(Z>0 && wireframe){
                  if(editable) interfaceBufferQueue=[...interfaceBufferQueue, a]
                  x.closePath()
                  x.lineWidth=Math.min(200,500/(Z**1.35))|0
                  //x.globalAlpha=.3*globalAlpha
                  //x.strokeStyle=strokeStyle
                  //x.stroke()
                  x.lineWidth/=7
                  x.lineWidth=Math.max(x.lineWidth|0, 1)
                  x.globalAlpha=1*globalAlpha
                  x.strokeStyle=strokeStyle
                  x.stroke()
                }
              }
            })

            
            let sel=editable && tools.selectMode=='shape'&&(dod.filter(v=>v[v.length-1]).length)
            dod.map((v,i)=>{
              if(useFill){
                if(v.length){
                  x.beginPath()
                  v.map((q,j)=>{
                    X=q[0]
                    Y=q[1]
                    Z=q[2]
                    //R2(rl,pt,yw,0)
                    X+=sx
                    Y+=sy
                    Z+=sz
                    R(Rl,Pt,Yw,1)
                    if(Z>0)x.lineTo(...Q())
                  })
                  x.globalAlpha=globalAlpha
                  x.fillStyle=(v[v.length-1]&&tools.selectMode=='face'||sel?highlightColor:fillStyle)
                  x.fill()
                }
              }
            })
            
            // vertex hover
            if(editable && tools.selectMode == 'vertex'){
              dod.map((v,i)=>{
                if(v.length){
                  v.map((q,j)=>{
                    if(q[3]){
                      X=q[0]
                      Y=q[1]
                      Z=q[2]
                      //R2(rl,pt,yw,0)
                      X+=sx
                      Y+=sy
                      Z+=sz
                      R(Rl,Pt,Yw,1)
                      if(Z>0){
                        l=Q()
                        s=Math.max(10, Math.min(100, 200/(1+Z)))
                        x.fillStyle=highlightColor
                        x.fillRect(l[0]-s/2,l[1]-s/2,s,s)
                      }
                    }
                    //if(!draggingShape) q[3]=false
                  })
                }
              })
            }
          }

          Sphere=(ls,cl,rw)=>{
            let X,Y,Z
            let ret=[]
            for(i=cl*rw;i--;){
              a=[]
              j=i%cl
              k=(i/cl|0)
              l=(j+1)%cl
              
              X=S(p=Math.PI*2/cl*j)*S(q=Math.PI/rw*k)*ls
              Y=C(q)*ls
              Z=C(p)*S(q)*ls
              a=[...a,[X,Y,Z]]
              X=S(p=Math.PI*2/cl*l)*S(q=Math.PI/rw*k)*ls
              Y=C(q)*ls
              Z=C(p)*S(q)*ls
              a=[...a,[X,Y,Z]]
              X=S(p=Math.PI*2/cl*l)*S(q=Math.PI/rw*(k+1))*ls
              Y=C(q)*ls
              Z=C(p)*S(q)*ls
              a=[...a,[X,Y,Z]]
              X=S(p=Math.PI*2/cl*j)*S(q=Math.PI/rw*(k+1))*ls
              Y=C(q)*ls
              Z=C(p)*S(q)*ls
              a=[...a,[X,Y,Z]]
              
              ret = [...ret, a]
            }
            return ret
          }

          Tetrahedron=ls=>{
            let ret=[]
            let a = []
            let theta=1.2217304763960306
            for(let i=3;i--;){
              X=S(p=Math.PI*2/3*i)
              Y=C(p)+.5
              Z=0
              R2(0,-theta/2,0)
              a=[...a, [X,Y,Z]]
            }
            ret=[...ret, a]
            b=JSON.parse(JSON.stringify(a))
            ax=ay=az=0
            b.map(v=>{
              X=v[0]
              Y=v[1]
              Z=v[2]
              R2(0,theta,0)
              v[0]=X
              v[1]=Y
              v[2]=Z
            })
            ret=[...ret, b]
            ct=0
            ret.map(v=>{
              v.map(q=>{
                ax+=q[0]
                ay+=q[1]
                az+=q[2]
                ct++
              })
            })
            ax/=ct
            ay/=ct
            az/=ct
            ret.map(v=>{
              v.map(q=>{
                q[0]-=ax*1.5
                q[1]-=ay*1.5
                q[2]-=az*1.5
              })
            })

            b=JSON.parse(JSON.stringify(ret))
            b.map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R2(0,Math.PI,Math.PI/2)
                q[0]=X
                q[1]=Y
                q[2]=Z
              })
            })
            ret=[...ret, ...b]
            
            ret.map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R2(0,.96,0)
                R2(0,0,t*5)
                R2(0,Math.PI,0)
                q[0]=X
                q[1]=Y
                q[2]=Z
                d=Math.hypot(...q)
                q[0]/=d
                q[1]/=d
                q[2]/=d
                q[0]*=ls
                q[1]*=ls
                q[2]*=ls
              })
            })
            return ret
          }
          
          Octahedron=ls=>{
            ret = []
            a=[]
            for(i=4;i--;){
              X1=S(p=Math.PI*2/4*i+Math.PI/4)
              Y1=C(p)
              Z1=0
              X2=S(p=Math.PI*2/4*(i+1)+Math.PI/4)
              Y2=C(p)
              Z2=0
              X3=0
              Y3=0
              Z3=1
              a=[
                [X1,Y1,Z1],
                [X2,Y2,Z2],
                [X3,Y3,Z3]
              ]
              ret=[...ret, a]
              a=[
                [X1,Y1,-Z1],
                [X2,Y2,-Z2],
                [X3,Y3,-Z3]
              ]
              ret=[...ret, a]
            }
            ret.map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R2(0,Math.PI/2,Math.PI/2)
                q[0]=X
                q[1]=Y
                q[2]=Z
                d=Math.hypot(...q)
                q[0]/=d
                q[1]/=d
                q[2]/=d
                q[0]*=ls
                q[1]*=ls
                q[2]*=ls
              })
            })
            return ret
          }
          
          Cube=ls=>{
            let ret=[]
            for(j=6;j--;ret=[...ret,b])for(b=[],i=4;i--;)b=[...b,[(a=[S(p=Math.PI/2*i+Math.PI/4),C(p),2**.5/2])[j%3]*(l=j<3?-ls:ls),a[(j+1)%3]*l,a[(j+2)%3]*l]]
            ret.map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R2(0,0,t*5)
                q[0]=X
                q[1]=Y
                q[2]=Z
                d=Math.hypot(...q)
                q[0]/=d
                q[1]/=d
                q[2]/=d
                q[0]*=ls
                q[1]*=ls
                q[2]*=ls
              })
            })
            return ret
          }
          
          Icosahedron=ls=>{
            let a=[1,1],ret=[]
            let b
            for(i=40;i--;)a=[...a,a[l=a.length-1]+a[l-1]];
            let phi=a[l]/a[l-1]
            a=[[[-phi,-1,0],[phi,-1,0],[phi,1,0],[-phi,1,0]],[[0,-phi,-1],[0,phi,-1],[0,phi,1],[0,-phi,1]],[[-1,0,-phi],[-1,0,phi],[1,0,phi],[1,0,-phi]]]
            let ico=[[[0,1],[1,0],[1,3]],[[0,1],[2,3],[1,0]],[[2,0],[2,3],[1,0]],[[0,1],[2,3],[0,2]],[[1,1],[2,3],[0,2]],[[1,1],[2,3],[2,0]],[[1,1],[1,2],[0,2]],[[0,1],[2,2],[0,2]],[[0,0],[1,0],[2,0]],[[2,0],[0,3],[0,0]],[[1,1],[1,2],[0,3]],[[1,1],[2,0],[0,3]],[[0,1],[1,3],[2,2]],[[1,3],[2,1],[2,2]],[[2,1],[0,3],[1,2]],[[2,1],[0,0],[1,3]],[[1,2],[2,2],[2,1]],[[2,2],[1,2],[0,2]],[[0,3],[2,1],[0,0]],[[1,3],[1,0],[0,0]]]
            ico.map((v,i)=>{
              b=[]
              v.map(q=>{
                t1=q[0],t2=q[1]
                X=a[t1][t2][0],Y=a[t1][t2][1],Z=a[t1][t2][2]
                b=[...b, [X,Y,Z]]
              })
              ret=[...ret, b]
            })
            ret.map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R2(0,0,Math.PI/2)
                q[0]=X
                q[1]=Y
                q[2]=Z
                d=Math.hypot(...q)
                q[0]/=d
                q[1]/=d
                q[2]/=d
                q[0]*=ls
                q[1]*=ls
                q[2]*=ls
              })
            })
            return ret
          }

          Dodecahedron=ls=>{
            let ret=[]
            let sd=5
            let a=[], b=[]
            mind=6e6
            for(let i=sd;i--;){
              X=S(p=Math.PI*2/sd*i)
              Y=C(p)
              Z=0
              if(Y<mind)mind=Y
              a = [...a, [X,Y,Z]]
            }
            a=a.map(v=>{
              X=v[0]
              Y=v[1]-=mind
              Z=v[2]
              R2(0,.5535735,0)
              return [X,Y,Z]
            })
            
            ret = [...ret, a]
            b=JSON.parse(JSON.stringify(a)).map(v=>{
              d=Math.hypot(v[0],v[1])
              v[0]=S(p=Math.atan2(v[0],v[1])+Math.PI)*d
              v[1]=C(p)*d
              return v
            })
            ret = [...ret, b]
            
            ret.map(v=>{
              v.map(q=>{
                if(q[2]<mind)mind=q[2]
              })
            })
            ret.map(v=>{
              v.map(q=>{
                q[2]+=ang=1.538840639715
              })
            })
            b=JSON.parse(JSON.stringify(ret)).map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R2(Math.PI,0,Math.PI)
                q[0]=X
                q[1]=Y
                q[2]=Z
              })
              return v
            })
            e=JSON.parse(JSON.stringify(ret = [...ret, ...b]))
            
            b=JSON.parse(JSON.stringify(ret)).map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R2(0,Math.PI/2,Math.PI/2)
                q[0]=X
                q[1]=Y
                q[2]=Z
              })
              return v
            })
            ret = [...ret, ...b]

            b=JSON.parse(JSON.stringify(e)).map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R2(Math.PI/2,0,Math.PI/2)
                q[0]=X
                q[1]=Y
                q[2]=Z
              })
              return v
            })
            ret = [...ret, ...b]
            
            ret.map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R2(0,0,t*5)
                q[0]=X
                q[1]=Y
                q[2]=Z
                d=Math.hypot(...q)
                q[0]/=d
                q[1]/=d
                q[2]/=d
                q[0]*=ls
                q[1]*=ls
                q[2]*=ls
              })
            })
            return ret
          }
    
          bcl=32,brw=32,sp=(2**.5)*4
          a=[]
          B=Array(bcl*brw+1).fill().map((v,i)=>{
            tx=((i%bcl)-bcl/2+.5)*sp
            ty=0
            tz=((i/bcl|0)-brw/2+.5)*sp
            sp=2**.5/2*4
            for(j=4;j--;){
              X=S(p=Math.PI*2/4*j+Math.PI/4)/2*4
              Y=0
              Z=C(p)/2*4
              a=[...a, [tx,ty,tz,X,Y,Z]]
            }
          })
          B=a
          ls=.05
          tools.gyro.y = 50+10
          Gyro=Array(3).fill().map((v,i)=>{
            return Array(20).fill().map((q,j)=>{
              a=[S(p=Math.PI*2/20*j)*ls,C(p)*ls,0]
              return [a[i],a[(i+1)%3],a[(i+2)%3]]
            })
          })
          oRl=Rl=0,oPt=Pt=0,oYw=Yw=0
          ooX=oX=0,ooY=oY=3,ooZ=oZ=0
          
          iSc=0, G=30, shape_ls=3
          
          
          defaultGyro = () => {
            rsd=20, ret=[], axisLength=4
            a=[]
            for(let i=rsd;i--;){
              X=S(p=Math.PI*2/rsd*i)*axisLength
              Y=0
              Z=C(p)*axisLength
              a=[...a, [X,Y,Z]]
            }
            ret=[...ret, a]
            a=[]
            for(let i=rsd;i--;){
              X=0
              Y=S(p=Math.PI*2/rsd*i)*axisLength
              Z=C(p)*axisLength
              a=[...a, [X,Y,Z]]
            }
            ret=[...ret, a]
            a=[]
            for(let i=rsd;i--;){
              X=S(p=Math.PI*2/rsd*i)*axisLength
              Y=C(p)*axisLength
              Z=0
              a=[...a, [X,Y,Z]]
            }
            ret=[...ret, a]
          
            return ret
          }
          
          gGyro=defaultGyro()
          
          base_shapes=[
            {shape: Tetrahedron(shape_ls), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: Cube(shape_ls), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: Octahedron(shape_ls), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: Dodecahedron(shape_ls), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: Icosahedron(shape_ls), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: Sphere(shape_ls, 10, 8), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: await loadOBJ('https://cantelope.org/chair.obj', 8, 0, 0, 0, 0, 0, Math.PI), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            //{shape: await loadOBJ('https://cantelope.org/wolf.obj', .005, 0, 0, 0, 0, 0, Math.PI), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: await loadOBJ('https://cantelope.org/tiger.obj', .01, 2, 0, 0, 0, 0, Math.PI/4+Math.PI/2), color: '#fc02', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: await loadOBJ('https://cantelope.org/fish.obj', 2, 0, 0, 0, 0, 0, Math.PI/2), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: await loadOBJ('https://cantelope.org/WoodHouse.obj', 8, 0, 0, 0, 0, 0,0), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            //{shape: await loadOBJ('https://cantelope.org/gymnast.obj', 1, 0, 0, 0, 0, Math.PI/2, 0), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            //{shape: await loadOBJ('https://cantelope.org/angel.obj', 1, 0, 0, 0, 0, Math.PI/2, 0), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: Sphere(shape_ls, 10*1.5*1|0, 8*1.5*1|0), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: Sphere(shape_ls, 10*1.5*2|0, 8*1.5*2|0), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: Sphere(shape_ls, 10*1.5*3|0, 8*1.5*3|0), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: Sphere(shape_ls, 10*1.5*4|0, 8*1.5*4|0), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            //{shape: await loadOBJ('https://cantelope.org/stool.obj', 1.5, 0, 0, 0, 0, 0,0), color: '#4403', wireframe: true, useFill: true, gyro: defaultGyro()},
            //{shape: await loadOBJ('https://cantelope.org/eyeball.obj', 1.5, 0, 0, 0, 0, 0,0), color: '#4403', wireframe: false, useFill: true, gyro: defaultGyro()},
            {shape: await loadOBJ('https://cantelope.org/axe.obj', 20, 0, 0, 0, 0, 0,Math.PI/2), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: await loadOBJ('https://cantelope.org/pickaxe.obj', 20, 0, 0, 0, 0, 0,Math.PI/2), color: '#2063', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: await loadOBJ('https://cantelope.org/tree1.obj', 1, 0, 0, 0, 0, 0,Math.PI/2), color: '#2063', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: await loadReducedOBJ('https://cantelope.org/reduceObjPolys/fish.obj', 2,0,0,0,0,0,Math.PI/2, 80), color: '#2063', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: await loadOBJ('https://cantelope.org/house2.obj', 1, 0, 0, 0, 0, 0,0), color: '#f001', wireframe: true, useFill: true, gyro: defaultGyro()},
            {shape: await loadOBJ('https://cantelope.org/tree2.obj', .03, 0, 0, 0, 0, -Math.PI/2, 0), color: '#0633', wireframe: true, useFill: true, gyro: defaultGyro()},
            //{shape: await loadOBJ('https://cantelope.org/butterfly.obj', 150, 0, 0, 0, 0, 0, Math.PI/2), color: '#0643', wireframe: true, useFill: true, gyro: defaultGyro()},
            //{shape: await loadReducedOBJ('https://cantelope.org/reduceObjPolys/butterfly.obj', 50,0,0,0,0,0,Math.PI/2, 80), color: '#2063', wireframe: true, useFill: true, gyro: defaultGyro()},
            //{shape: await loadOBJ('https://cantelope.org/ladybug.obj', 1500, 5, 0, 0, 0, 0, Math.PI/2), color: '#f003', wireframe: true, useFill: true, gyro: defaultGyro()},
            //{shape: await loadReducedOBJ('https://cantelope.org/reduceObjPolys/ladybug.obj', 1500,-5,2,0,0,0,Math.PI/2, 80), color: '#2063', wireframe: true, useFill: true, gyro: defaultGyro()},   
          ] 
          pushShape=(shape, tx, ty, tz, rl, pt, yw, color, wireframe, useFill, gyro) =>  {

            shp = JSON.parse(JSON.stringify(shape))
            shp = shp.map(q=>{
              q=q.map(n=>{
                X=n[0]
                Y=n[1]
                Z=n[2]
                R(rl,0,0,0)
                R(0,pt,0,0)
                R(0,0,yw,0)
                //X+=tx
                //Y+=ty
                //Z+=tz
                return [X,Y,Z]
              })
              return q
            })
            shp.push(false)
            shp.map(v=>{
              if(v){
                v.map(q=>{
                  q.push(false)
                })
                v.push(false)
              }
            })
            states = JSON.parse(JSON.stringify(shp)).map(v=>{
              if(v){
                return v.map(q=>{
                  if(q){
                    q.map(n=>{
                      return [Array(4).fill(false)]
                    })
                    return [q, false]
                  }
                })
              }
            })
            shps = [[tx, ty, tz, 0,0,0, shp, states, color, wireframe, useFill, gyro, false], ...shps]
          }
          
          initConfig = 0
          rl=pt=yw=0
          shps=[]
          let shp, states, color
          for(let i=0;i<iSc+1;i++){
            id=11
            let shape = base_shapes[id].shape
            color = base_shapes[id].color
            gyro = base_shapes[id].gyro
            wireframe = base_shapes[id].wireframe
            useFill = base_shapes[id].useFill
            if(i==iSc){
              X=0
              Y=0
              Z=0
              shp = JSON.parse(JSON.stringify(Sphere(10,20,12)))
            }else{
              switch(initConfig){
                case 0:
                  tx=0
                  ty=-shape_ls/1.5*3/2
                  tz=0
                  rl=pt=yw=0
                  shp = JSON.parse(JSON.stringify(shape))
                  shp = shp.map(q=>{
                    q=q.map(n=>{
                      X=n[0]
                      Y=n[1]
                      Z=n[2]
                      //R(Math.PI*2/(iSc)*i,0,0,0)
                      return [X,Y,Z]
                    })
                    return q
                  })
                break
                case 1: //random everything
                  tx=(Rn()-.5)*G
                  ty=-shape_ls/1.5-(Rn())*shape_ls*10
                  tz=(Rn()-.5)*G
                  rl=pt=yw=0
                  shp = JSON.parse(JSON.stringify(shape))
                  shp = shp.map(q=>{
                    q=q.map(n=>{
                      X=n[0]
                      Y=n[1]
                      Z=n[2]
                      R(Math.PI*2/(iSc)*i,0,0,0)
                      return [X,Y,Z]
                    })
                    return q
                  })
                break
                case 2:
                  tx=0
                  ty=-shape_ls/1.5
                  tz=i*shape_ls*4
                  rl=pt=yw=0
                  shp = JSON.parse(JSON.stringify(shape))
                  shp = shp.map(q=>{
                    q=q.map(n=>{
                      X=n[0]
                      Y=n[1]
                      Z=n[2]
                      R(Math.PI*2/(iSc)*i,0,0,0)
                      return [X,Y,Z]
                    })
                    return q
                  })
                break
                case 3:
                  tx=S(p=Math.PI*2/iSc*(i-1))*20
                  ty=0
                  tz=C(p)*20
                  rl=pt=yw=0
                  shp = JSON.parse(JSON.stringify(shape))
                  shp = shp.map(q=>{
                    q=q.map(n=>{
                      X=n[0]
                      Y=n[1]
                      Z=n[2]
                      R(0,0, Math.PI*2/iSc*(i-1),0)
                      return [X,Y,Z]
                    })
                    return q
                  })
                  maxY=-6e6
                  shp.map(v=>{
                    if(v.length){
                      v.map((q,j)=>{
                        if((Y=q[1])>maxY){
                          maxY=Y
                        }
                      })
                    }
                  })
                  ty=-maxY
                break
                case 4:
                  tx=S(p=Math.PI*2/25*(i-1))*(ls=2000/(.1+((i+1)**.5)*500))
                  ty=-i/4
                  tz=C(p)*ls
                  rl=pt=yw=0
                  shp = JSON.parse(JSON.stringify(shape))
                  shp = shp.map(q=>{
                    q=q.map(n=>{
                      X=n[0]*ls/6
                      Y=n[1]*ls/6
                      Z=n[2]*ls/6
                      R(0,0, Math.PI*2/iSc*(i-1),0)
                      return [X,Y,Z]
                    })
                    return q
                  })
                break
              }
              X=tx
              Y=ty
              Z=tz
            }
            shp.push(false)
            shp.map(v=>{
              if(v){
                v.map(q=>{
                  q.push(false)
                })
                v.push(false)
              }
            })
            states = JSON.parse(JSON.stringify(shp)).map(v=>{
              if(v){
                return v.map(q=>{
                  if(q){
                    q.map(n=>{
                      return [Array(4).fill(false)]
                    })
                    return [q, false]
                  }
                })
              }
            })
            shps = [...shps, [X, Y, Z, rl, pt, yw, shp, states, color, wireframe, useFill, gyro, false]]
          }
          
          //for(let m=1;m--;) pushShape(base_shapes[9].shape, 5, -4,0, 0, .4, Math.PI/2, '#0643', wireframe, gyro)
          //for(let m=3;m--;) pushShape(base_shapes[8].shape, (Rn()-.5)*G, -2-Rn()*G/4, (Rn()-.5)*G, 0,0,Rn()*Math.PI*2, '#0643', wireframe, gyro)

          interfaceBuffer=Array(shps.length-1).fill().map((v,i)=>{
            return [[],[],0,i]
          })

          oXv=oYv=oZv=0
          Rlv=Ptv=Ywv=0
          mv=.06,rv=.024
        }
        selectAll=()=>{
          gGyro=defaultGyro()
          allSelected = true
          ct=cct=0
          tools.marq.x=tools.marq.y=0
          tools.marq.w=c.width
          tools.marq.h=c.height
          shps.filter((v,i)=>i<shps.length-1).map(v=>{
            v[6].map(q=>{
              q[q.length-1]=true
              cct++
            })
            v[v.length-1]=true
          })
        }
        
        checkHover=()=>{
          ct=0
          cct=0
          tools.hover=false
          interfaceBuffer.map((v,i,a)=>{
            if(1||i<interfaceBuffer.length-1){
              let p
              if(tools.selectMode !== 'vertex'){
                switch(tools.selectMode){
                  case 'shape':
                    ct=(shps.filter(v=>{
                      return v[6].filter(q=>{
                        return q[q.length-1]
                      }).length
                    }).length)
                    v=interfaceBuffer[interfaceBuffer.length-1-i]
                  break
                  case 'face':
                    //v=interfaceBuffer[interfaceBuffer.length-1-i]
                    //shps.map(v=>{
                    //  v[6].map(q=>{
                    //    q[q.length-1]=false
                    //  })
                    //})
                  break
                }
              }
              
              if(1||tools.marq.x!==-1){
                v[1].map((q,j)=>{
                  if(q.length<v[1].length-1){
                    if(tools.selectMode == 'face') v[0][j][v[0][j].length-1]=false
                    p=[]
                    a=[]
                    inFront=true
                    q.map((n, m)=>{
                      if(typeof v[0][j][m] !== 'undefined'){
                        if(n[2]<0)inFront=false
                        if(n.length>2){
                          if(tools.selectMode=='vertex'){
                            v[0][j][m][3]=n[2]>0&&Math.hypot(mx-n[0], my-n[1])<10
                            if(v[0][j][m][3]) tools.hover = true
                          }else{
                            p=[...p, Math.atan2(mx-n[0], my-n[1])+Math.PI]
                          }
                        }
                      }
                    })
                    if(inFront && tools.selectMode != 'vertex' && (!ct || cct)){
                      p.sort((a,b)=>a-b)
                      p.map((v,i)=>{if(i)a=[...a, p[i]-p[i-1]]})
                      p[0]+=Math.PI*2
                      a=[...a, p[0]-p[p.length-1]]
                      v[0][j][v[0][j].length-1]=!a.filter(v=>v>Math.PI).length
                      if(v[0][j][v[0][j].length-1]) tools.hover = true
                    }
                    q.map((n, m)=>{
                      if(typeof v[0][j][m] !== 'undefined' && n.length>2 && n[2]>=0){
                        if((tools.selectMode != 'vertex' || tools.marq.x!==-1) && tools.marq.w!=-1){
                            X1=Math.min(tools.marq.x,tools.marq.x+tools.marq.w)
                            X2=Math.max(tools.marq.x,tools.marq.x+tools.marq.w)
                            Y1=Math.min(tools.marq.y,tools.marq.y+tools.marq.h)
                            Y2=Math.max(tools.marq.y,tools.marq.y+tools.marq.h)
                            v[0][j][m][3]=n[0]>X1&&n[0]<X2&&n[1]>Y1&&n[1]<Y2
                        } else {
                           if(tools.selectMode != 'vertex'){
                             v[0][j][m][3]=!a.filter(v=>v>Math.PI).length
                           }
                        }
                        if(v[0][j][m][3]){
                          cct++
                          if(tools.selectMode != 'vertex') v[0][j][v[0][j].length-1]=true
                        }
                        if(v[0][j][v[0][j].length-1] && Math.hypot(mx-n[0], my-n[1])<10){
                          tools.hover=true
                        }
                      }
                    })
                  }
                })
              }
            }
          })
          if(tools.selectMode=='face' && !cct){
            shps.map((v,i)=>{
              v[6].map(q=>{
                tt=0
                shps.map(v=>tt+=v[6].filter(q=>q[q.length-1]).length)
                if(tt>1){
                  q[q.length-1]=false
                }
              })
            })
          }
          if(tools.selectMode=='shape'){
            shps.map((v,i)=>{
              v[v.length-1]=false
              v[6].map(q=>{
                if(q[q.length-1]) v[v.length-1] = true
              })
            })
          }
        }
        
        document.body.onfocus=()=>{
          keys[18]=false
        }
        
        run=keys[16]?3:1
        keys.map((v,i)=>{
          if(v){
            switch(i){
              case 90:
                Rlv-=rv
              break
              case 82:
                tools.transformMode = 'rotate'
              break
              case 77:
                tools.transformMode = 'move'
              break
              case 76:
                tools.transformMode = 'scale'
              break
              case 88:
                Rlv+=rv
              break
              case 67:
                oXv=oYv=oZv=Rlv=Ptv=Ywv=0
                Rl=oRl,Pt=oPt,Yw=oYw
                oX=ooX,oY=ooY,oZ=ooZ
              break
              case 37:
                if(keys[18]){
                  oXv+=S(p=-Yw+Math.PI/2)*mv*run
                  oZv+=C(p)*mv*run
                }else{
                  if(0&&keys[16]){
                    Rlv-=rv
                  }else{
                    Ywv+=rv
                  }
                }
              break
              case 38:
                if(keys[17]){
                  oYv+=mv*run
                } else {
                  Ptv+=rv
                }
              break
              case 39:
                if(keys[18]){
                  oXv-=S(p=-Yw+Math.PI/2)*mv*run
                  oZv-=C(p)*mv*run
                }else{
                  if(0&&keys[16]){
                    Rlv+=rv
                  }else{
                    Ywv-=rv
                  }
                }
              break
              case 40:
                if(keys[17]){
                  oYv-=mv*run
                } else {
                  Ptv-=rv
                }
              break
              case 87: //w
                if(0&&keys[16]){
                  oYv+=mv*run
                } else {
                  oXv-=S(p=-Yw)*C(q=Pt)*mv*run
                  if(tools.fly.enabled) oYv+=S(q)*mv*run
                  oZv-=C(p)*C(q)*mv*run
                }
              break
              case 65: //a
                if(keys[17]){
                  selectAll()
                } else {
                  oXv+=S(p=-Yw+Math.PI/2)*mv*run
                  oZv+=C(p)*mv*run
                }
              break
              case 83: //s
                if(0&&keys[16]){
                  oYv-=mv*run
                } else {
                  oXv+=S(p=-Yw)*C(q=Pt)*mv*run
                  if(tools.fly.enabled) oYv-=S(q)*mv*run
                  oZv+=C(p)*C(q)*mv*run
                }
              break
              case 68: //d
                if(keys[17]){
                  if(!shiftCloneComplete){
                    selectedShapes().map(v=>{
                      duplicateShape(v)
                    })
                    shiftCloneComplete=true
                  }
                }else{
                  oXv-=S(p=-Yw+Math.PI/2)*mv*run
                  oZv-=C(p)*mv*run
                }
              break
            }
          }
        })
                
        Rl+=Rlv/=1.3
        Pt+=Ptv/=1.3
        Yw+=Ywv/=1.3
        //Pt=Math.min(Math.PI/2, Math.max(-Math.PI/2,Pt))

        oX+=oXv/=1.1
        oY+=oYv/=1.1
        oZ+=oZv/=1.1
        
        x.clearRect(0,0,c.width,c.height)
        x.globalAlpha=1
        x.fillStyle='#111c'
        x.fillRect(0,0,c.width,c.height)
        if(tools.showGlobalSphere){
          shps.map((v,i)=>{
            if(i==shps.length-1){
              X=v[0]-oX
              Y=v[1]-oY
              Z=v[2]-oZ
              if(H(-X-oX,-Y-oY,-Z-77)<500){
                DrawSolid(v[6], X, Y, Z, v[3], v[4], v[5], '#ccffee44', '#000c', 1, 1/(1+H(-oX-X,-oY-Y,-oZ-Z)**9/59999999999999999999999), false, 1)
              }
            }
          })
        }
        
        if(tools.showFloor){
          B.map((v,i)=>{
            if(i>3){
              if(-oX-v[0]<-sp*bcl/2)v[0]-=sp*bcl
              if(-oX-v[0]>sp*bcl/2)v[0]+=sp*bcl
              if(-oZ-v[2]<-sp*brw/2)v[2]-=sp*brw
              if(-oZ-v[2]>sp*brw/2)v[2]+=sp*brw
              if(!(i%4))x.beginPath()
              X=(v[0]+v[3])
              Y=v[1]+v[4]
              Z=(v[2]+v[5])
              R(Rl,Pt,Yw,1)
              if(Z>0)x.lineTo(...Q())
              if(i%4==3){
                x.closePath()
                x.lineWidth=Math.min(200, 50/(Z**1.5))
                //x.strokeStyle='#fff1'
                //x.stroke()
                x.lineWidth/=6
                x.lineWidth=Math.max(1,x.lineWidth)
                x.globalAlpha=(1/(1+H(-oX-v[0],-oZ-v[2])**10/1e15))*Math.min(1, Math.max(.03, H(-oX-v[0],-oZ-v[2])**2/50))
                x.strokeStyle='#fff4'
                x.stroke()
                x.fillStyle=((i/4|0)%bcl+i/4/bcl|0)%2?'#111c':'#222c'
                x.fill()
              }
            }
          })
          highlightColor='#0f43'
        }
        //highlightColor=`hsla(${t*1000},99%,50%,.2)`
        


        // model data section
        interfaceBuffer_ = Array(shps.length).fill().map((v,i)=>{
          return [[],[],0,i]
        })

        editable = !(tools.gyro.hover || tools.move.hover || tools.info.hover || tools.menu.hover)
        
        shps=[...shps.filter((v,i)=>i<shps.length-1).sort((a,b)=>{
          X=-oX-a[0]
          Y=-oY-a[1]
          Z=-oZ-a[2]
          R(Rl,Pt,Yw,1)
          Z1=Z
          X=-oX-b[0]
          Y=-oY-b[1]
          Z=-oZ-b[2]
          R(Rl,Pt,Yw,1)
          Z2=Z
          return Z1-Z2
        }), shps[shps.length-1]]
        
        shps.map((v,i)=>{
          if(i<shps.length-1){// && interfaceBuffer[i].length>2){
            let n = shps[interfaceBuffer[i][3]]
            if(typeof n !== 'undefined'){
              X=n[0]
              Y=n[1]
              Z=n[2]
              if(H(-X-oX,-Y-oY,-Z-oZ)<500){
                interfaceBufferQueue = []
                DrawSolid(n[6], X, Y, Z, n[3], n[4], n[5], '#fff2', n[8], n[10], 1/(1+H(-oX-X,-oY-Y,-oZ-Z)**9/59999999999999999999999), editable, n[9])
                X=n[0]
                Y=n[1]
                Z=n[2]
                R(Rl,Pt,Yw,1)
                if(editable) interfaceBuffer_=[...interfaceBuffer_, [n[6], [...interfaceBufferQueue, Z, interfaceBuffer[i][3]]]]
              }
            //} else {
            //    if(interfaceBuffer_=[...interfaceBuffer_, [v[6], [0, Z, i]]]
            }
          }
        })
        interfaceBuffer = interfaceBuffer_

        l=selectedShapes()
        if(l.length){
          tlj=x.lineJoin
          axisLength=4
          x.lineJoin=x.lineCap='round'
          cx=cy=cz=tx=ty=tz=rl=pt=yw=ct=0
          l.map((v,i)=>{
            switch(tools.selectMode){
              case 'face':
              case 'vertex':
                //if(v[6].length){
                  v[6].map(q=>{
                    if(q.length){
                      q.map(n=>{
                        if(n.length && n[3]){
                          tx+=n[0]
                          ty+=n[1]
                          tz+=n[2]
                          ct++
                        }
                      })
                    }
                  })
                //}
              break
              case 'shape':
                ct++
              break
            }
            cx+=v[0]
            cy+=v[1]
            cz+=v[2]
            
          })
          if(l.length>1 && mbuttons[0]){
            gGyro.map(v=>{
              v.map(q=>{
                X=q[0]
                Y=q[1]
                Z=q[2]
                R(Rl,Pt,Yw,0)
                if(keys[16]){
                  R2(-d1/20,-d2/20,0,0)
                }else{
                  R2(0,-d2/20,-d1/20,0)
                }
                R(-Rl,-Pt,-Yw,0)
                q[0]=X
                q[1]=Y
                q[2]=Z
              })
            })
          }
          tx/=ct
          ty/=ct
          tz/=ct
          X=tx+=cx/l.length
          Y=ty+=cy/l.length
          Z=tz+=cz/l.length
          R(Rl,Pt,Yw,1)
          if(Z>0){
            switch(tools.transformMode){
              case 'move':
                x.beginPath()
                X=tx
                Y=ty
                Z=tz
                R(Rl,Pt,Yw,1)
                x.lineTo(...Q())
                X=tx+axisLength
                Y=ty
                Z=tz
                R(Rl,Pt,Yw,1)
                x.lineTo(...Q())
                x.strokeStyle='#f003'
                x.lineWidth=300/Z
                x.stroke()
                x.lineWidth/=6
                x.strokeStyle='#fff8'
                x.stroke()
                x.beginPath()
                X=tx
                Y=ty
                Z=tz
                R(Rl,Pt,Yw,1)
                x.lineTo(...Q())
                X=tx
                Y=ty-axisLength
                Z=tz
                R(Rl,Pt,Yw,1)
                x.lineTo(...Q())
                x.strokeStyle='#0f03'
                x.lineWidth=300/Z
                x.stroke()
                x.lineWidth/=6
                x.strokeStyle='#fff8'
                x.stroke()
                x.beginPath()
                X=tx
                Y=ty
                Z=tz
                R(Rl,Pt,Yw,1)
                x.lineTo(...Q())
                X=tx
                Y=ty
                Z=tz-axisLength
                R(Rl,Pt,Yw,1)
                x.lineTo(...Q())
                x.strokeStyle='#04f3'
                x.lineWidth=300/Z
                x.stroke()
                x.lineWidth/=6
                x.strokeStyle='#fff8'
                x.stroke()
              break
              case 'rotate':
                if(l.length==1){
                  if(typeof l[0][11] !== 'undefined'){
                    l[0][11].map((v,i)=>{
                      x.beginPath()
                      v.map(q=>{
                        X=q[0]+tx
                        Y=q[1]+ty
                        Z=q[2]+tz
                        R(Rl,Pt,Yw,1)
                        if(Z>0) x.lineTo(...Q())
                      })
                      x.closePath()
                      switch(i){
                        case 0: x.strokeStyle='#0f03'; break
                        case 1: x.strokeStyle='#04f3'; break
                        case 2: x.strokeStyle='#f003'; break
                      }
                      x.lineWidth=300/Z
                      x.stroke()
                      x.lineWidth/=6
                      x.strokeStyle='#fff8'
                      x.stroke()
                    })
                  }
                } else {
                  gGyro.map((v,i)=>{
                    x.beginPath()
                    v.map(q=>{
                      X=q[0]+tx
                      Y=q[1]+ty
                      Z=q[2]+tz
                      R(Rl,Pt,Yw,1)
                      if(Z>0) x.lineTo(...Q())
                    })
                    x.closePath()
                    switch(i){
                      case 0: x.strokeStyle='#0f03'; break
                      case 1: x.strokeStyle='#04f3'; break
                      case 2: x.strokeStyle='#f003'; break
                    }
                    x.lineWidth=300/Z
                    x.stroke()
                    x.lineWidth/=6
                    x.strokeStyle='#fff8'
                    x.stroke()
                  })
                }
              break
              case 'scale':
              break
            }
            //x.arc(...Q(),200/Z,0,7)
            //x.fillStyle='#0f8'
            //x.fill()
          }
          x.lineJoin=x.lineCap=tlj
        }
        
        if(!allSelected && !draggingShape && tools.selectMode=='shape'){
          shps.map(v=>{
            v[6].map(q=>{
              q[q.length-1]=false
            })
          })
        }
        
        if(!draggingShape || (!mbuttons[0] && !cct)){
          checkHover()
        }
        if(tools.menu.visible){
          if(mbuttons[2]){
            tools.menu.visible = !tools.menu.visible
            tools.menu.toggled = true
          } else {
            drawMenu()
            //x2.drawImage(c,0,0,canvas2.width,canvas2.height)
            //requestAnimationFrame(Draw)
            //return
          }
        }
        
        if(tools.menu.visible){
          //if(menuHover()
        }

        if(tools.show){
          s=100
          x.globalAlpha=tools.move.hover?1:.5
          x.drawImage(moveIcon, tools.move.x-s/2,tools.move.y-s/2,s,s)
          x.globalAlpha=1
          
          x.lineJoin=x.lineCap='round'
          Gyro.map((v,i)=>{
            x.beginPath()
            v.map(q=>{
              X=q[0]
              Y=q[1]
              Z=q[2]
              R(Rl,Pt,Yw)
              Z+=1
              l=Q()
              x.lineTo(l[0]+tools.gyro.x,-c.height/2+l[1]+tools.gyro.y)
            })
            x.closePath()
            x.lineWidth=10/(Z**1.5)
            x.strokeStyle=`hsla(${360/3*i},99%,50%,${tools.gyro.hover?.4:.2})`
            x.stroke()
            x.lineWidth/=5
            x.strokeStyle=`hsla(${360/3*i},99%,50%,${tools.gyro.hover?.8:.4})`
            x.stroke()
          })
          
          
          x.strokeStyle='#fff3'
          x.fillStyle='#0006'
          x.strokeRect(...(a=[tools.info.x-10, tools.info.y-10, 250, tools.info.visible?940:46]))
          x.fillRect(...a)
          x.lineJoin=x.lineCap='butt'
          x.font=tools.info.fontSize+'px courier'
          x.fillStyle='#8fc'
          
          x.globalAlpha=.6
          x.drawImage(tools.info.visible?hideIcon:showIcon,...(tools.info.toggleButton=[a[0]+a[2]-45,a[1]+3,40,40]))
          x.globalAlpha=1
          
          if(!tools.info.visible){
            printLn(`INFO -`, 0, 0, 'info')
          } else {
            let rw=0
            printLn(`CAMERA -`, rw, 0, 'info')
            printLn(`POSITION`, rw+=2, 1, 'info')
            printLn(`X = ${Math.round(oX*1e3)/1e3}`, ++rw, 2, 'info')
            printLn(`Y = ${Math.round(oY*1e3)/1e3}`, ++rw, 2, 'info')
            printLn(`Z = ${Math.round(oZ*1e3)/1e3}`, ++rw, 2, 'info')
            printLn(`ORIENTATION`, rw+=2, 1, 'info')
            printLn(`ROLL  = ${Math.round(Rl*1e3)/1e3}`, ++rw, 2, 'info')
            printLn(`PITCH = ${Math.round(Pt*1e3)/1e3}`, ++rw, 2, 'info')
            printLn(`YAW   = ${Math.round(Yw*1e3)/1e3}`, ++rw, 2, 'info')

            printLn(`MODES -`, rw+=2, 0, 'info')
            printLn(`TOOLS [T] = ${tools.show}`, ++rw, 1, 'info')
            printLn(`Y-LOCK[F] = ${tools.fly.enabled}`, ++rw, 1, 'info')
            printLn(`VWFNDR[V] = ${tools.viewfinder.visible}`, ++rw, 1, 'info')
            printLn(`SELECT MODE -`, rw+=2, 0, 'info')
            printLn(`${tools.selectMode}`, ++rw, 1, 'info')
          }
        }
        if(tools.viewfinder.visible){
          x.globalAlpha=tools.viewfinder.alpha
          x.drawImage(tools.viewfinder.img,0,0,c.width,c.height)
          x.globalAlpha=1
        }
        
        if(tools.marq.x!=-1){
          x.strokeStyle='#4f84'
          x.lineWidth=4
          x.strokeRect(tools.marq.x,tools.marq.y,tools.marq.w,tools.marq.h)
          x.fillStyle='#0f41'
          x.fillRect(tools.marq.x,tools.marq.y,tools.marq.w,tools.marq.h)
        }

        if(mbuttons[2]){
          drawMenu()
          mbuttons[2]=false
        }

        if(showCursorDot){
          s=15
          x.fillStyle='#4f8a'
          x.fillRect(mx-s/2,my-s/2,s,s)
        }
        
        freshClick=false
        t+=1/60
      }

      if(stage2 && loaded){

        if(!recordingStarted && export_base) startRecording()

        switch(visualization){
          case 0:
            scaleX=2
            framels=5
            if(vid){
              x.globalAlpha=.5
              x.drawImage(bgvid,0,0,w=c.width,c.height)
              x.globalAlpha=1
            }else{
              x.fillStyle='#0006'
              x.fillRect(0,0,w=c.width,w)
            }
            
            G=2
            oX=0,oY=0,oZ=7+S(t)*2
            Rl=S(t/1.5)/4,Pt=S(t)/4,Yw=S(t*1.5)/2
            if(playing){
              B = new Uint8Array(bufferLength)
              analyser.getByteFrequencyData(B)
            } else {
              trim=0
              B=Array(64).fill(1)
            }

            x.beginPath()
            x.lineWidth=framels
            X=G*scaleX,Y=G,Z=0,R(Rl,Pt,Yw,1)
            x.lineTo(...Q())
            X=G*scaleX,Y=-G,Z=0,R(Rl,Pt,Yw,1)
            x.lineTo(...Q())
            X=-G*scaleX,Y=-G,Z=0,R(Rl,Pt,Yw,1)
            x.lineTo(...Q())
            X=-G*scaleX,Y=G,Z=0,R(Rl,Pt,Yw,1)
            x.lineTo(...Q())
            X=G*scaleX,Y=G,Z=0,R(Rl,Pt,Yw,1)
            x.lineTo(...Q())
            x.strokeStyle=`hsla(${t*99},99%,70%,.1`
            x.fillStyle=`hsla(${t*99},99%,20%,.2`
            x.stroke()
            x.fill()

            B.map((v,i)=>{
              if(v&&i<B.length-trim){
                X=(G/(B.length-trim)*(i+.5)-G/2)*scaleX*2
                Y=G/128*(128-v/1.1+1)-(G/10)
                Z=0
                R(Rl,Pt,Yw,1)
                x.beginPath()
                x.lineTo(...Q())
                X=(G/(B.length-trim)*(i+.5)-G/2)*scaleX*2
                Y=G-(G/10)
                Z=0
                R(Rl,Pt,Yw,1)
                x.lineTo(...Q())
                x.strokeStyle=`hsla(${360/(B.length-trim)*i+360/128*v+t*900},50%,${40+50/1e9*(v**4)}%,.4)`
                x.lineWidth=750/(1+Z)/(2+(Z)/3.5)
                x.stroke()
                x.strokeStyle=`hsla(${360/(B.length-trim)*i+360/128*v+t*900},50%,${60+40/1e9*(v**4)}%,1)`
                x.lineWidth=350/(1+Z)/(2+(Z)/3.5)
                x.stroke()
              }
            })
          t+= 1/60
          break
          case 1:
          
            if(vid){
              x.globalAlpha=.5
              x.drawImage(bgvid,0,0,w=c.width,c.height)
              x.globalAlpha=1
              x.fillStyle='#0004'
              x.fillRect(0,0,w=c.width,w)
            }else{
              x.fillStyle='#0006'
              x.fillRect(0,0,w=c.width,w)
            }

            G=2
            oX=0,oY=0,oZ=G+S(t*2)
            Rl=t,Pt=0,Yw=C(t/2)
            
            if(playing){
              B = new Uint8Array(bufferLength)
              analyser.getByteFrequencyData(B)
              iPc=B.length-trim
            }else{
              iPc=32,B=Array(iPc).fill(0)
            }
            

            if(!t){
              x.lineCap=x.lineJoin='round'
              P=Array(iPc).fill().map((v,i)=>{
                X=G/iPc*(i-iPc/2)*1
                Y=G/3
                Z=0
                return [X,Y,Z,1,[]]
              })
            }

            P.map((v,k)=>{
              ty=v[1]+(B[k]-128)/250
              X=v[0]
              Y=ty
              Z=v[2]
              R(Rl,Pt,Yw,1)
              x.beginPath()
              x.lineTo(...Q())
              v[4].map((q,j)=>{
                X=v[4][l=v[4].length-j-1][0]
                Y=v[4][l][1]
                Z=v[4][l][2]
                v[4][l][2]+=.08
                R(Rl,Pt,Yw,1)
                if(j<v[4].length-1){
                  x.beginPath()
                  x.lineTo(...Q())
                  X=v[4][l=v[4].length-j-2][0]
                  Y=v[4][l][1]
                  Z=v[4][l][2]
                  R(Rl,Pt,Yw,1)
                  x.lineTo(...Q())
                  x.strokeStyle=`hsla(${360/128*B[k]-t*800+j*10},99%,${150-60/128*B[k]}%,.4)`
                  x.lineWidth=30*v[4][l][3]/Z
                  if(Z>.5)x.stroke()
                }
                v[4][l][3]/=1.05
              })
              v[4].push([v[0],ty,v[2],v[3]/1.05])
              v[4]=v[4].filter(v=>v[3]>.05)
            })
            P.map((v,k)=>{
              X=v[0]
              Y=-v[1]
              Z=v[2]
              R(Rl,Pt,Yw,1)
              x.beginPath()
              x.lineTo(...Q())
              v[4].map((q,j)=>{
                X=v[4][l=v[4].length-j-1][0]
                Y=-v[4][l][1]
                Z=v[4][l][2]
                R(Rl,Pt,Yw,1)
                if(j<v[4].length-1){
                  x.beginPath()
                  x.lineTo(...Q())
                  X=v[4][l=v[4].length-j-2][0]
                  Y=-v[4][l][1]
                  Z=v[4][l][2]
                  R(Rl,Pt,Yw,1)
                  x.lineTo(...Q())
                  x.strokeStyle=`hsla(${360/128*B[k]+t*800+j*10},99%,${150-60/128*B[k]}%,.4)`
                  x.lineWidth=30*v[4][l][3]/Z
                  if(Z>.5)x.stroke()
                }
              })
            })
            
            t+= 1/60
          break
          
          case 2:
          
            scaleX=2
            framels=5
            if(vid){
              x.globalAlpha=.5
              x.drawImage(bgvid,0,0,w=c.width,c.height);
              x.globalAlpha=1
              x.fillStyle='#0005'
              x.fillRect(0,0,w=c.width,w)
            }else{
              x.fillStyle='#0004'
              x.fillRect(0,0,w=c.width,w)
            }
            
            oX=0,oY=0,oZ=10
            Rl=0,Pt=-t/1.5,Yw=t
            if(playing){
              B = new Uint8Array(bufferLength)
              analyser.getByteFrequencyData(B)
            } else {
              trim=0
              B=Array(64).fill(1)
            }

            if(!t){
              x.lineJoin=x.lineCap='round'
              cl=30,rw=16,iSr=2
              P=Array(cl*(rw+1)|0).fill().map((v,i)=>{
                j=i/cl|0
                X=S(p=Math.PI*2/cl*i)*S(q=Math.PI/(rw)*j)*iSr
                Y=C(q)*iSr
                Z=C(p)*S(q)*iSr
                return [X,Y,Z,0]
              })
            }
            P.map((v,i)=>{
              
              d3=Math.hypot(...v)
              v[1]=v[1]/d3*iSr
              v[0]=v[0]/d3*iSr*(d1=1+B[l=cl/B.length*(i%cl)|0]**2/19999/(((1+Math.abs(v[1]))**.5*5))*3)
              v[1]=v[1]*d1
              v[2]=v[2]/d3*iSr*d1

              X=v[0]
              Y=v[1]
              Z=v[2]
              R(Rl,Pt,Yw,1)
              x.beginPath()
              x.lineTo(...Q())
              if((i%cl)==cl-1){
                X=P[l=i-cl+1][0]
                Y=P[l][1]
                Z=P[l][2]
              } else {
                X=P[i+1][0]
                Y=P[i+1][1]
                Z=P[i+1][2]
              }
              R(Rl,Pt,Yw,1)
              x.lineTo(...Q())
              x.strokeStyle=`hsla(${d3*80-t*399},99%,${Math.max(40,95-(1+d3)**5/70)}%,.1`
              x.lineWidth=1+999/Z/Z
              x.stroke()
              x.strokeStyle=`hsla(${d3*80-t*399},99%,${Math.max(40,115-(1+d3)**5/70)}%,.6`
              x.lineWidth=1+200/Z/Z
              x.stroke()

              if(i<P.length-cl){
                if((i+1)%cl){
                  X=P[l=i+cl+1][0]
                  Y=P[l][1]
                  Z=P[l][2]
                }else{
                  X=P[l=i+1][0]
                  Y=P[l][1]
                  Z=P[l][2]
                }
                R(Rl,Pt,Yw,1)
                x.lineTo(...Q())
                x.strokeStyle=`hsla(${d3*80-t*399},99%,${Math.max(40,95-(1+d3)**5/70)}%,.1`
                x.lineWidth=1+999/Z/Z
                x.stroke()
                x.strokeStyle=`hsla(${d3*80-t*399},99%,${Math.max(40,115-(1+d3)**5/70)}%,.6`
                x.lineWidth=1+200/Z/Z
                x.stroke()
              }
            })
          
            t+= 1/60
          break
          case 3:
            x.fillStyle='#0008',x.fillRect(0,0,w=c.width,w)
            
            if(!t){
              x.lineJoin=x.lineCap='round'
              cl=100,iBc=16,iBr=10,iBv=16
              rw=cl/aspect
              ls=w/cl
              P=Array(cl*(rw+1)|0).fill().map((v,i)=>[(i%cl)*ls,(i/cl|0)*ls])
              E=Array(iBc).fill().map(v=>[iBr+Rn()*(w-iBr*2),iBr+Rn()*(c.height-iBr*2),S(p=Rn()*Math.PI)*iBv,C(p)*iBv])
            }

            if(playing){
              B = new Uint8Array(bufferLength)
              analyser.getByteFrequencyData(B)
              amp=0
              B.map((v,i)=>amp+=v*(1+(B.length/2-Math.abs(B.length/2-i))/2))
            } else {
              trim=0
              B=Array(64).fill(1)
            }

            P.map(v=>{
              e=0
              E.map(q=>{
                d=Math.hypot(q[0]-(v[0]+ls/2),q[1]-(v[1]+ls/2))
                e+=5/(d**1.35/600)*(1+amp**4/300000000000000)
              })
              x.fillStyle=`hsla(${e*1.5-65},99%,${Math.min(100,e)}%,1`
              x.fillRect(...v,ls+1,ls+1)
              /*
              x.strokeStyle='#fff1'
              x.lineWidth=ls/10
              x.strokeRect(...v,ls,ls)
              */
            })

            E.map(v=>{
              if(v[0]>w-iBr||v[0]<iBr)v[2]*=-1
              if(v[1]>c.height-iBr||v[1]<iBr)v[3]*=-1
              x.beginPath()
              x.arc(v[0],v[1],iBr,0,7)
              x.fillStyle='#aff6'
              x.fill()
              x.beginPath()
              x.arc(v[0],v[1],iBr/1.75,0,7)
              x.fillStyle='#6aa8'
              x.fill()
              v[0]+=v[2]
              v[1]+=v[3]
            })
            
            t+=1/60
          break
          case 4:
            x.lineJoin=x.lineCap='round'
            if(playing){
              B = new Uint8Array(bufferLength)
              analyser.getByteFrequencyData(B)
              amp=0
              B.map((v,i)=>{amp+=v*(1+(B.length/2-Math.abs(B.length/2-i))/2)})
            } else {
              trim=0
              B=Array(fftSize).fill(1)
            }
            Q=q=>{
              with(x)
              beginPath(),
              lineWidth=w/Z**3.15*(o>-1?20:20.5),
              moveTo(q[0],q[1]),
              lineTo(q[2],q[3])
              if(i>l-3){
                lineWidth=w/2**2.5*v,
                x.moveTo(w-q[5]/14*h,h+4/14*h*q[4][0]),
                x.lineTo(q[0],q[1])
              }
              x.stroke()
            }
            R=s=>{
              for(n=3;n--;){
                ls=1
                if(playing){
                  B.map((v,i)=>{
                    switch(2-n){
                      case 0: if(i<fftSize/3)ls+=v/2000; break
                      case 1: if(i>=fftSize/3 && i<fftSize/3*2)ls+=v/400; break
                      case 2: if(i<=fftSize-fftSize/3)ls+=v/2000; break
                    }
                  })
                }
                ls=ls**4
                ls=1+ls/10
                for(k=2;k--;){
                  for(l=i=24;i--;){
                    X=S(p=(Math.PI/2+Math.PI*2/38*i)*(g=k?1:-1))-1*g
                    Y=(C(p)-2)*ls
                    p=Math.atan2(X,0)-t*4
                    d=(X*X)**.5*ls
                    ox=-12+n*12
                    X=S(q=p+Math.PI*2/9*s[1]*1.425+t*2)*d*s[0]-ox
                    Z=C(q)*d+14
                    D=w+X/Z*h
                    E=h+(Y+2)/Z*h*s[0]
                    //x.strokeStyle=`rgba(${255},${v=(o=s[2]>-1)?150:3},${v},${.85/((s[0]/2)**3*(o?8:1))}`
                    v=(o=s[2]>-1)?150:3
                    if(s[0]==1){
                      x.strokeStyle='#f008'
                    }else{
                      x.strokeStyle=`hsla(${255+360/24*i+t*500},99%,${120-Math.min(50,s[2]**3)}%,${.5/((s[0]/2)**3*(o?8:1))}`
                    }
                    if(i-l+1)Q([A,G,D,E,s,ox,ls])
                    A=D,G=E
                  }
                }
              }
          }
          if(typeof bgvid !== 'undefined'){
            x.globalAlpha=.3
            x.drawImage(bgvid,0,0,(w=c.width/2)*2,c.height)
            x.globalAlpha=1
            x.fillStyle='#0002'
            x.fillRect(0,0,w*2,w*2)
          }else{
            x.fillStyle='#0002'
            x.fillRect(0,0,(w=c.width/2)*2,c.height)
          }
          h=c.height/2
          R([1,0])
          for(j=16;j--;)R([1+(v=j/5.3+(t*1.5)%(1/5.3)),v,j])

          t+=1/60
          break
          
          case 5:

            if(typeof bgvid !== 'undefined'){
              x.globalAlpha=.5
              x.drawImage(bgvid,0,0,w=c.width,c.height)
              x.globalAlpha=1
              //x.fillStyle='#0000'
              //x.fillRect(0,0,w=c.width,w)
            }else{
              x.fillStyle='#0004'
              x.fillRect(0,0,w=c.width,w)
            }

            Rl=t/6,Pt=S(t/2)/2,Yw=S(t/1.5)/1.5
            oX=0,oY=0,oZ=4+S(t/1.5)/1.75
            mt=S(t/4)
            
            if(!t){
              I=(A,B,M,D,E,F,G,H)=>(K=((G-E)*(B-F)-(H-F)*(A-E))/(J=(H-F)*(M-A)-(G-E)*(D-B)))>=0&&K<=1&&(L=((M-A)*(B-F)-(D-B)*(A-E))/J)>=0&&L<=1?[A+K*(M-A),B+K*(D-B)]:0
              x.lineJoin=x.lineCap='round'
              sd=6
              iBr=2,iPv=.006,iPsv=.025,iPc=8,iPs=150,iRm=iPv*65,iRsm=iPsv*2.75,iPbv=.02
              B=Array(sd).fill().map((v,i)=>{
                return [S(p=Math.PI*2/sd*i)*iBr,C(p)*iBr,0]
              })
              P=Array(iPc).fill().map(v=>{return {X:0,Y:0,Z:0,vx:S(p=Rn()*Math.PI*2)*iPv,vy:C(p)*iPv,vz:0,theta:0,P:[]}})
            }

            P.map((v,j)=>{
              x3_=v.X+=v.vx
              y3_=v.Y+=v.vy
              z3_=v.Z+=v.vz
              d=Math.hypot(v.vx,v.vy,v.vz)
              vxd=v.vx/d*iRm
              vyd=v.vy/d*iRm
              vzd=v.vz/d*iRm
              x4_=x3_+vxd
              y4_=y3_+vyd
              z4_=z3_+vzd
              B.map((q,i)=>{
                if(i){
                  X=B[i-1][0]
                  Y=B[i-1][1]
                  Z=0
                }else{
                  X=B[l=B.length-1][0]
                  Y=B[l][1]
                  Z=0
                }
                R(mt,0,0,0)
                x2_=X,y2_=Y
                X=q[0],Y=q[1],Z=0
                R(mt,0,0,0)
                x1_=X,y1_=Y
                if(a=I(x1_,y1_,x2_,y2_,x3_,y3_,x4_,y4_)){
                  u=((x3_-x1_)*(x2_-x1_)+(y3_-y1_)*(y2_-y1_))/Math.hypot(x2_-x1_,y2_-y1_)
                  mx=u*(x2_-x1_)
                  my=u*(y2_-y1_)
                  v.vx+=S(p=Math.atan2(mx-v.X,my-v.Y)-Math.PI/2)/80
                  v.vy+=C(p)/80
                }
              })
              d=Math.hypot(v.vx,v.vy)
              v.vx/=d
              v.vy/=d
              v.vx*=iPv
              v.vy*=iPv
              for(m=3;m--;)v.P.push([v.X,v.Y,v.Z,S(p=v.theta+=Math.PI/sd*(1+j==3?5:1+j)+S(t/1.5)/16)*iPsv,C(p)*iPsv,0,iPs])
              v.P.map(v=>{

                x3_=v[0]
                y3_=v[1]
                z3_=v[2]
                d=Math.hypot(v[3],v[4],v[5])
                vxd=v[3]/d*iRsm
                vyd=v[4]/d*iRsm
                vzd=v[5]/d*iRsm
                x4_=x3_+vxd
                y4_=y3_+vyd
                z4_=z3_+vzd
                bnc=0
                B.map((q,i)=>{
                  if(i){
                    X=B[i-1][0]
                    Y=B[i-1][1]
                    Z=0
                  }else{
                    X=B[l=B.length-1][0]
                    Y=B[l][1]
                    Z=0
                  }
                  R(mt,0,0,0)
                  x2_=X,y2_=Y
                  X=q[0],Y=q[1],Z=0
                  R(mt,0,0,0)
                  x1_=X,y1_=Y
                  if(!bnc&&(a=I(x1_,y1_,x2_,y2_,x3_,y3_,x4_,y4_))){
                    bnc=0
                    u=((x3_-x1_)*(x2_-x1_)+(y3_-y1_)*(y2_-y1_))/Math.hypot(x2_-x1_,y2_-y1_)
                    mx=u*(x2_-x1_)
                    my=u*(y2_-y1_)
                    v[3]+=S(p=Math.atan2(mx-v[0],my-v[1])-.8)/80*(d=.25+Math.hypot(v[3],v[4])*100)
                    v[4]+=C(p)/100*d
                  }
                })
                d=Math.hypot(v[3],v[4])
                v[3]/=d
                v[4]/=d
                v[3]*=iPsv
                v[4]*=iPsv

                X=v[0]+=v[3]
                Y=v[1]+=v[4]
                Z=v[2]+=v[5]
                R(Rl,Pt,Yw,1)
                if(Z>0){
                  x.beginPath()
                  x.arc(...Q(),Math.min(99,(v[6]-=1.5)/Z/Z),0,7)
                  x.fillStyle='#4f84'
                  x.fill()
                }
              })
              v.P=v.P.filter(v=>v[6]>10)
            })

            B.map((v,i)=>{
              x.beginPath()
              if(i){
                X=B[i-1][0]
                Y=B[i-1][1]
                Z=B[i-1][2]
              }else{
                X=B[l=B.length-1][0]
                Y=B[l][1]
                Z=B[l][2]
              }
              R(mt,0,0,0)
              R(Rl,Pt,Yw,1)
              x.lineTo(...Q())
              X=v[0]
              Y=v[1]
              Z=v[2]
              R(mt,0,0,0)
              R(Rl,Pt,Yw,1)
              x.lineTo(...Q())
              x.strokeStyle='#84f6'
              x.lineWidth=50/Z
              x.stroke()
            })

            t+=1/60
            
          break
          case 6:

            x.globalAlpha=.07*(.25-C(t*1.5)/4)
            x.drawImage(bgimg,0,0,w=c.width,1080)
            x.globalAlpha=1
            x.fillStyle='#00000018'
            x.fillRect(0,0,w,w)
            
            oX=0,oY=0,oZ=4
            Rl=0,Pt=-t/2,Yw=S(t/3)*4
            
            if(!t){
              iPv=.002,iPsv=.005,iPc=10,iPsf=5,iPss=2,iPrr=.025
              G=2
              SP=v=>[S(p=Math.PI*2*Rn())*S(q=Math.PI*(Rn()**.5)/-2+Math.PI*(Rn()<.5?0:1))*v,C(q)*v,C(p)*S(q)*v]
              P=Array(iPc).fill().map(v=>[G*(Rn()-.5),G*(Rn()-.5),G*(Rn()-.5),...SP(iPv),[]])
            }
            
            if(playing){
              B = new Uint8Array(bufferLength)
              analyser.getByteFrequencyData(B)
              amp=0
              B.map((v,i)=>amp+=v*5**4)
              amp/=550000
            } else {
              trim=0
              B=Array(64).fill(1)
            }

            P.map((v,j)=>{
              v[0]+=v[3]
              v[1]+=v[4]
              v[2]+=v[5]
              if(v[0]<-G/2||v[0]>G/2)v[3]*=-1
              if(v[1]<-G/2||v[1]>G/2)v[4]*=-1
              if(v[2]<-G/2||v[2]>G/2)v[5]*=-1
              for(m=iPsf;m--;)v[6]=[...v[6],[v[0],v[1],v[2],...SP(iPsv),iPss]]
              v[6]=v[6].filter(q=>q[6]>.3)
              v[6].map(q=>{
                d1=Math.hypot(q[0]-v[0],q[1]-v[1],q[2]-v[2])
                X=v[0]+(q[0]+=q[3]*=1.04)
                Y=v[1]+(q[1]+=q[4]*=1.04)
                Z=v[2]+(q[2]+=q[5]*=1.04)
                R(Rl,Pt,Yw,1)
                if(Z>0){
                  x.fillStyle=`hsla(${d1*120-t*400+360/P.length*j},99%,${Math.max(50, amp*15+88-((d1+1)**3*20))}%,${.05/(1+(1+d1)**4/350)})`
                  l=Q(),s=Math.min(500,(.75+amp**3.5/40)*(q[6]-=iPrr)*150/Z/Z)
                  x.fillRect(l[0]-s/2,l[1]-s/2,s,s)
                }
              })
            })
            
            t+=1/60

          break;
          case 7:
          
            if(!t){
              R=(Rl,Pt,Yw,o)=>{
                X=S(p=(A=(M=Math).atan2)(X,Y)+Rl)*(d=(H=M.hypot)(X,Y)),Y=C(p)*d,Y=S(p=A(Y,Z)+Pt)*(d=H(Y,Z)),Z=C(p)*d,X=S(p=A(X,Z)+Yw)*(d=H(X,Z)),Z=C(p)*d
                if(o)X+=oX,Y+=oY,Z+=oZ
              }
              Q=q=>[c.width/2+X/Z*800,c.height/2+Y/Z*800]
              Rn=Math.random
            }
            
            if(typeof bgvid !== 'undefined'){
              x.globalAlpha=.4
              x.drawImage(bgvid,0,0,w=c.width,c.height)
              x.globalAlpha=1
              x.fillStyle='#0006'
              x.fillRect(0,0,w=c.width,w)
            }else{
              x.fillStyle='#0006'
              x.fillRect(0,0,w=c.width,w)
            }

            oX=0,oY=0,oZ=14+S(t*3)*4
            Rl=t/4,Pt=0,Yw=t
            iBCsc=3
            G=6

            x.lineJoin=x.lineCap='round'
            if(!t){
              BC=[(-G/2|0)+.5,(G/2|0)+.5,0,[],1,0]
              for(i=6;i--;){
                for(j=4;j--;){
                  BC[3]=[...BC[3],[
                    (a=[S(p=Math.PI*2/4*j+Math.PI/4)*(d=(2**.5)/2),C(p)*d,.5])[i%3]*(l=i<3?1:-1),
                    a[(i+1)%3]*l,
                    a[(i+2)%3]*l]
                  ]
                }
              }
              BCs=Array(iBCsc).fill().map(v=>[JSON.parse(JSON.stringify(BC))])
            }

            if(1){
              BCs.map((q,j)=>{
                for(m=1;m--;){
                  a=JSON.parse(JSON.stringify(q[q.length-1]))
                  n=a[5]
                  if(!(((t*60+j*(20/BCs.length))|0)%3)) n=a[5]=Rn()*6|0
                  tx=a[0]
                  ty=a[1]
                  tz=a[2]
                  tx+=(n==0?1:0)
                  ty+=(n==1?1:0)
                  tz+=(n==2?1:0)
                  tx+=(n==3?-1:0)
                  ty+=(n==4?-1:0)
                  tz+=(n==5?-1:0)
                  tx=Math.max(-G,tx)
                  ty=Math.max(-G,ty)
                  tz=Math.max(-G,tz)
                  tx=Math.min(G,tx)
                  ty=Math.min(G,ty)
                  tz=Math.min(G,tz)
                  a[0]=tx,a[1]=ty,a[2]=tz
                  a[4]=1
                  q.push(a)
                }
              })
            }

            BCs=BCs.map((v,j)=>{
              v.map(v=>{
                a=v[4]/=1.3
                x.strokeStyle=`hsla(${0},99%,${115-(1-a)*75}%,${a/4}`
                x.fillStyle=`hsla(${0},99%,${115-(1-a)*75}%,${a/8}`
                for(m=8;m--;){
                  switch(m){
                    case 0:
                      tx=v[0]
                      ty=v[1]
                      tz=v[2]
                      break
                    case 1:
                      tx=v[0]*-1
                      ty=v[1]
                      tz=v[2]
                      break
                    case 2:
                      tx=v[0]*-1
                      ty=v[1]*-1
                      tz=v[2]
                      break
                    case 3:
                      tx=v[0]*-1
                      ty=v[1]*-1
                      tz=v[2]*-1
                      break
                    case 4:
                      tx=v[0]
                      ty=v[1]*-1
                      tz=v[2]*-1
                      break
                    case 5:
                      tx=v[0]
                      ty=v[1]
                      tz=v[2]*-1
                      break
                    case 6:
                      tx=v[0]*-1
                      ty=v[1]
                      tz=v[2]*-1
                      break
                    case 7:
                      tx=v[0]
                      ty=v[1]*-1
                      tz=v[2]
                      break
                  }
                  v[3].map((v,i)=>{
                    if(i%4==0)x.beginPath()
                    X=v[0]+tx
                    Y=v[1]+ty
                    Z=v[2]+tz
                    R(Rl,Pt,Yw,1)
                    if(Z>0){
                      x.lineTo(...Q())
                      if(i%4==3){
                        x.closePath()
                        x.lineWidth=Math.min(50,1+599/Z/Z)
                        x.stroke()
                        x.fill()
                      }
                    }
                  })
                }
              })
              return v.filter(v=>v[4]>.08)
            })            
            t+=1/60
            
          break
          
          case 8:
          
            if(typeof bgvid !== 'undefined'){
              x.globalAlpha=.6
              x.drawImage(bgvid,0,0,w=c.width,c.height);
              x.globalAlpha=1
            }else{
              x.fillStyle='#0001'
              x.fillRect(0,0,w=c.width,w)
            }
            if(!t){
              iR=c.width/1.5,iV=7,sql=.15,iS=24
              P=[]
            }

            for(m=10;m--;)P=[...P,[c.width/2+S(p=Math.PI*2*Rn())*iR*.95,c.height/2+C(p)*iR*(c.height/c.width),-S(p)*iV,-C(p)*iV,iS,Rn()*80]]
            P.map((v,i)=>{
              aX=v[0]+=v[2]=S(p=Math.atan2(v[2],v[3])+(Rn()-.5)*sql)*iV
              aY=v[1]+=v[3]=C(p)*iV
              x.beginPath()
              x.arc(aX,aY,Math.min(120,((v[4]/=1.01)+1)**2.65/26),0,7)
              x.fillStyle=`hsla(${140+v[5]},99%,${4+Math.hypot(c.width/2-aX,c.height/2-aY)/iR*20}%,${.08*Math.hypot(c.width/2-aX,c.height/2-aY)/iR}`
              x.fill()
            })
            P=P.filter(v=>v[4]>5)

            t+=1/60
          break
          
          case 9:
            if(vid){
              x.globalAlpha=.8
              x.drawImage(bgvid,0,0,w=c.width,c.height)
              x.globalAlpha=1
              x.fillStyle='#0003'
              x.fillRect(0,0,w=c.width,w)
            }else{
              x.fillStyle='#0006'
              x.fillRect(0,0,w=c.width,w)
            }


            Rl=t/4,Pt=-t,Yw=t/2
            oX=0,oY=0,oZ=60+S(t*2)*16
            
            if(!t){
              x.lineJoin=x.lineCap='round'
              cl=3,rw=16*3,s=6
            }
            P=Array(cl).fill().map((v,j)=>{
              return Array(rw).fill().map((v,i)=>{
                l2=1
                l1=rw*l2/16
                d=s+S(Math.PI*2/rw*l1*i)*(.15+C(Math.PI*8/rw*l1*i))**3*2
                X=S(p=Math.PI*2/(rw)*l2*i)*d
                Y=C(p)*d
                Z=0
                R(0,0,Math.PI*2/cl*j,0)
                return [X,Y,Z]
              })
            })
            
            for(k=3**3;k--;){
              tx=((k%3)-1.5+.5)*s*4
              ty=(((k/3|0)%3)-1.5+.5)*s*4
              tz=((k/3/3|0)-1.5+.5)*s*4
              d3=Math.hypot(tx,ty,tz)/16+t*2
              P.map((v,j)=>{
                v.map((q,i)=>{
                  x.beginPath()
                  X=q[0]
                  Y=q[1]
                  Z=q[2]
                  R(t+d3,-t/2+d3,t/3+d3,0)
                  X+=tx,Y+=ty,Z+=tz
                  R(Rl,Pt,Yw,1)
                  x.lineTo(...Q())
                  if(i<v.length-1){
                    X=v[i+1][0]
                    Y=v[i+1][1]
                    Z=v[i+1][2]
                  }else{
                    X=v[0][0]
                    Y=v[0][1]
                    Z=v[0][2]
                  }
                  R(t+d3,-t/2+d3,t/3+d3,0)
                  X+=tx,Y+=ty,Z+=tz
                  R(Rl,Pt,Yw,1)
                  if(Z>5){
                    x.lineTo(...Q())
                    x.strokeStyle='#8fb3'
                    x.lineWidth=Math.min(32,1+50000/Z/Z)
                    x.stroke()
                    x.strokeStyle='#eef8'
                    x.lineWidth=Math.min(12,1+5000/Z/Z)
                    x.stroke()
                  }
                })
              })
            }

            t+=1/60

          break
          
          case 10:

            if(typeof bgvid !== 'undefined'){
              x.globalAlpha=.9
              x.drawImage(bgvid,0,0,w=c.width,c.height);
              x.globalAlpha=1
            }else{
              x.fillStyle='#0006'
              x.fillRect(0,0,w=c.width,w)
            }
            if(!t){
              iR=c.width/1.5,iV=7,sql=.15,iS=24
              P=[]
            }

            for(m=10;m--;)P=[...P,[c.width/2+S(p=Math.PI*2*Rn())*iR*.95,c.height/2+C(p)*iR*(c.height/c.width),-S(p)*iV,-C(p)*iV,iS,Rn()*80]]
            P.map((v,i)=>{
              aX=v[0]+=v[2]=S(p=Math.atan2(v[2],v[3])+(Rn()-.5)*sql)*iV
              aY=v[1]+=v[3]=C(p)*iV
              x.beginPath()
              x.arc(aX,aY,Math.min(120,((v[4]/=1.01)+1)**2.65/26),0,7)
              x.fillStyle=`hsla(${140+v[5]},99%,${4+Math.hypot(c.width/2-aX,c.height/2-aY)/iR*20}%,${.08*Math.hypot(c.width/2-aX,c.height/2-aY)/iR}`
              x.fill()
            })
            P=P.filter(v=>v[4]>5)

            t+=1/60

          break;
          
          case 11:

            if(typeof bgvid !== 'undefined'){
              x.globalAlpha=.3
              x.drawImage(bgvid,0,0,w=c.width,c.height+3);
              x.globalAlpha=1
              x.fillStyle='#0002'
              x.fillRect(0,0,w=c.width,w)
            }else{
              x.fillStyle='#000'
              x.fillRect(0,0,w=c.width,w)
            }
            if(!t){
              x.fillStyle='#000'
              x.fillRect(0,0,w=c.width,w)
            }

            oX=0,oY=0,oZ=16+S(t)*6
            Rl=0,Pt=-t/2,Yw=t

            Rnd=q=>(((q+23))**3.1)%1

            if(!t){
              x.lineJoin=x.lineCap='butt'
              iBr=9,G=8,iPc=3,iPv=.3,iPs=35,iBc=w/3.5|0
              B=Array(iBc).fill().map((v,i)=>{
                s=iBr*((.0001+Rnd(i*4+3))**(1/3))
                X=S(p1=Math.PI*2*Rnd(i*4))*S(p2=Math.PI/2*(Rnd(i*4+1)**.5)-Math.PI*(Rnd(i*4+2)<.5?1:0))*s
                Y=C(p2)*s
                Z=C(p1)*S(p2)*s
                return [X,Y,Z]
              })
              B=B.map(v=>{
                v[3]=[]
                B.map(q=>{
                  v[3].push(Math.hypot(q[0]-v[0],q[1]-v[1],q[2]-v[2]))
                })
                return v
              })
              P=Array(iPc).fill().map((v,i)=>{
                X=(Rnd(i*9+0)-.5)*G
                Y=(Rnd(i*9+1)-.5)*G
                Z=(Rnd(i*9+2)-.5)*G
                return [X,Y,Z,(Rnd(i*9+3)-.5)*iPv,(Rnd(i*9+4)-.5)*iPv,(Rnd(i*9+5)-.5)*iPv]
              })
            }

            B.map((v,i)=>{
              tx=v[0]
              ty=v[1]
              tz=v[2]
              v[3].map((q,j)=>{
                d=.000001+Math.hypot(B[j][0]-v[0],B[j][1]-v[1],B[j][2]-v[2])
                tx-=(B[j][0]-v[0])*(q-d)
                ty-=(B[j][1]-v[1])*(q-d)
                tz-=(B[j][2]-v[2])*(q-d)
              })
              tx/=B.length
              ty/=B.length
              tz/=B.length
              v[0]=v[0]+(tx-v[0])/4
              v[1]=v[1]+(ty-v[1])/4
              v[2]=v[2]+(tz-v[2])/4
              P.map(q=>{
                d=Math.hypot(q[0]-v[0],q[1]-v[1],q[2]-v[2])
                if(d<iPs/6){
                  v[0]=q[0]+(v[0]-q[0])/d*iPs/6
                  v[1]=q[1]+(v[1]-q[1])/d*iPs/6
                  v[2]=q[2]+(v[2]-q[2])/d*iPs/6
                }
              })
              tx=X=v[0]
              ty=Y=v[1]
              tz=Z=v[2]
              R(Rl,Pt,Yw,1)
              s=200/Z
              l=Q()
              x.globalAlpha=.5+S(t/1.75)/2
              x.fillStyle='#ffffff16'
              x.fillRect(l[0]-s*2,l[1]-s*2,s*4,s*4)
              x.fillStyle='#ffffff26'
              x.fillRect(l[0]-s,l[1]-s,s*2,s*2)
              x.fillStyle='#ffffffaf'
              x.fillRect(l[0]-s/3,l[1]-s/3,s/1.5,s/1.5)
            })
            x.globalAlpha=1

            P.map(v=>{
              X=v[0]+=v[3]
              Y=v[1]+=v[4]
              Z=v[2]+=v[5]
              if(v[0]>G)v[3]*=-1
              if(v[0]<-G)v[3]*=-1
              if(v[1]>G)v[4]*=-1
              if(v[1]<-G)v[4]*=-1
              if(v[2]>G)v[5]*=-1
              if(v[2]<-G)v[5]*=-1
              R(Rl,Pt,Yw,1)
              x.beginPath()
              if(Z>0){
                x.arc(...Q(),150*iPs/Z,0,7)
                x.fillStyle=`hsla(${160},99%,50%,${Math.max(0,S(t)/4)}`
                x.fill()
              }
            })
            
            t+=1/60
            
          break;
          
          case 12:
          
            if(!t){
              setTimeout(()=>go=1,400)
              go=0
            }
            if(vidplaying && go){
              x.globalAlpha = 1
              s=8-S(t/2)*7
              for(i=0;i<25;++i){
                X=960+((i%5)-2)*384*s-192*s
                Y=540+((i/5|0)-2)*216*s-108*s
                x.drawImage(bgvid,X,Y,384*s,216*s+5);
              }
              x.globalCompositeOperation='color-dodge'
              x.fillStyle=`#34f`
              x.fillRect(0,0,c.width,c.height)
              x.globalCompositeOperation='source-over'
              X=S(p=t*10)*(d=Math.hypot(960,540))
              Y=C(p)*(d=Math.hypot(960,540))
              g=x.createLinearGradient(960+X,540+Y,960-X,540-Y)
              g.addColorStop(0, `hsla(${t*300+0},99%,${50-S(t*3)*50}%,.4)`)
              g.addColorStop(1, `hsla(${t*300+180},99%,${50-S(t*6)*50}%,.4)`)
              x.fillStyle = g
              x.fillRect(0,0,w=c.width,w)
              x.fillStyle = `hsla(0,0%,0%,${.33+S(t*2)/3})`
              x.fillRect(0,0,w=c.width,w)
            }else{
              x.fillStyle='#000'
              x.fillRect(0,0,w=c.width,w)
            }
          
            x.lineCap='round',R=(n,r,o)=>{Y=S(p=(A=(M=Math).atan2)(Y,Z)+n)*(d=(H=M.hypot)(Y,Z)),Z=C(p)*d,X=S(p=A(X,Z)+r)*(d=H(X,Z)),Z=C(p)*d,Z+=o?6:0};Q=q=>[960+X/Z*L,540+Y/Z*L],a=99,V=1.15,W=.35,B=Array(L=600).fill().map((v,i)=>{j=i/2|0,q=(s=Math.PI)/a*3*j,v=s/a*3*(j+1),X=V*(2+C(q)),Y=S(q)*2,R(Z=0,s*2/a*j),D=X,E=Y,F=Z,X=V*(2+C(v)),Y=S(v)*2,R(Z=0,s*2/a*(j+1)),I=X,J=Y,K=Z
            U=A(I-D,K-F),e=M.acos((J-E)/H(I-D,J-E,K-F)),X=S(p=s*i+s*2/a*j*8)*W,Y=C(p)*W,R(s/2-e,U,Z=0);return[X+D,Y+E,Z+F]})
            for(n=(r=t)*2,j=2;j--;){B.map((v,i)=>{G=`hsla(${.9*i+t*L},99%,${75+S(.002*i+t*6)*35}%,.1)`,X=v[0],Y=v[1],Z=v[2],R(n,r,1);if(j){if(i<L&&!(i%2))x[h='beginPath']();x[O='lineTo'](...Q());if(i<533&&(i%2)==1){x[g='globalAlpha']=1,x[l='lineWidth']=1+50/Z/Z,x[s='strokeStyle']=T='#fffc',x[m='stroke'](),x[l]=L/Z/Z,x[s]=G,x[m]()}}else{if(i>=2){x[g]=1,x[h](),x[O](...Q()),X=B[i-2][0],Y=B[i-2][1],Z=B[i-2][2],R(n,r,1),x[O](...Q()),x[l]=1+50/Z/Z,x[s]=T,x[m](),x[l]=L/Z/Z,x[s]=G,x[m]()}}})}

            t+=1/60
          break
          
          case 13:

            oX=0,oY=0,oZ=12+C(t/2)*4
            Rl=t/2,Pt=-t/4,Yw=S(t/2.5)*10
            

            if(!t){
              go=false
              bgimg=new Image()
              bgimg.addEventListener('load',()=>{
                go=true
              })
              //bgimg.src='MlBFm.jpg'
              bgimg.src='./MlBFm.jpg'
            }

            if(go){
              x.globalAlpha=.075
              x.drawImage(bgimg, 0,0,c.width,c.height)
              x.globalAlpha=1
              x.fillStyle='#1013', x.fillRect(0,0,w=c.width,w)
            }else{
              x.fillStyle='#1026', x.fillRect(0,0,w=c.width,w)
            }
            
            Q=q=>[c.width/2+X/Z*400,c.height/2+Y/Z*400]
            
            if(playing){
              B = new Uint8Array(bufferLength)
              analyser.getByteFrequencyData(B)
              amp=0
              B.map((v,i)=>amp+=v*5**4)
              amp/=550000
            } else {
              trim=0
              B=Array(128).fill(1)
            }
            //B=B.map((v,i)=>{if(i)v=2000;return v})

            rw=50, col=50, sp=9
            for(m=2;m--;){
              for(i=rw*col;i--;){
                x.beginPath()
                j=i+1
                if(m){
                  X=((j%rw)/rw-.5)*sp
                  Z=((j/rw|0)/col-.5)*sp
                }else{
                  Z=((j%rw)/rw-.5)*sp
                  X=((j/rw|0)/col-.5)*sp
                }
                l=Math.hypot(X,Z)/(Math.hypot(rw,col))*1024|0
                d1=-(.75+S(Math.hypot(X,Z)*(3+S(t)*2)-t*10)/4)-B[l]/256*(1+l**.5)/2+.75+(l<2?-.6:0)
                ty=Y=d1*2
                X*=2
                Z*=2
                R(Rl,Pt,Yw,1)
                if(Z>0)x.lineTo(...Q())
                j=i+rw+1
                if(i<rw*col-rw){
                  if(m){
                    X=((j%rw)/rw-.5)*sp
                    Z=((j/rw|0)/col-.5)*sp
                  }else{
                    Z=((j%rw)/rw-.5)*sp
                    X=((j/rw|0)/col-.5)*sp
                  }
                  l=Math.hypot(X,Z)/(Math.hypot(rw,col))*1024|0
                  d=-(.75+S(Math.hypot(X,Z)*(3+S(t)*2)-t*10)/4)-B[l]/256*(1+l**.5)/2+.75+(l<2?-.6:0)
                  Y=d*2
                  X*=2
                  Z*=2
                  R(Rl,Pt,Yw,1)
                  if(Z>0)x.lineTo(...Q())
                }
                x.lineWidth=1
                x.strokeStyle=`hsla(${d1*200-t*300},99%,${70-Math.min(40,Math.abs(d1*25))+(1-ty)**4/3.5}%,${.1-d1/1.5})`
                x.stroke()
              }
            }
            t+=1/60
          break;
        }
      }

      x2.clearRect(0,0,w=canvas2.width|=0,w)
      //x2.save()
      //x2.translate(canvas2.width / 2, canvas2.height / 2)
      //x2.rotate(0)

      sc = 1
      //x2.drawImage(c, -canvas2.width/2*sc, -canvas2.height/2*sc, canvas2.width*sc, canvas2.height*sc)
      x2.drawImage(c,0,0,canvas2.width,canvas2.height)
      //x2.restore()
      requestAnimationFrame(Draw)
    }

    setupAnalyzerAndContent = () =>{
      analyzerSetup=1
      if(!recordingStarted){
        x.lineCap=x.lineJoin='round'
        loaded=0
        mp3 = new Audio()
        mp3.src = song
        if(!mp3)loaded=1
        mp3.addEventListener('canplay',()=>{
          if(!playing){
            loaded=playing=1
            audioCtx = new (window.AudioContext || window.webkitAudioContext)()
            analyser = audioCtx.createAnalyser()
            source = audioCtx.createMediaElementSource(mp3)
            source.connect(analyser)
            analyser.connect(audioCtx.destination)
            analyser.fftSize=fftSize
            trim=analyser.fftSize*.25
            bufferLength = analyser.frequencyBinCount
            mp3.loop = true
            mp3.play()
            if(vid){
              bgvid = document.createElement('video')
              bgvid.src = vid
              bgvid.addEventListener('canplay',()=>{
                if(!vidplaying){
                  vidplaying=1
                  bgvid.loop=true
                  bgvid.play()
                }
              })
            }
          }
        })
      }
    }


    startRecording = () => {
      console.log('recording started')
      recordingStarted=1
      const chunks = []
      const stream = canvas2.captureStream()
      const rec = new MediaRecorder(stream, {videoBitsPerSecond:12000000})
      rec.ondataavailable = e => chunks.push(e.data);
      rec.onstop = e => exportVid(new Blob(chunks, {type: 'video/webm'}));
      rec.start();
      setTimeout(()=>{
        rec.stop()
        canvas2.style.display='none'
        setTimeout(()=>{
          document.querySelectorAll('video').forEach(v=>{
            v.style.display = 'none'
          })
        },200)
        canvas2.style.display='none'
        console.log('recording stopped')
      }, duration);
    }

    exportVid = (blob) => {
      const vid = document.createElement('video')
      vid.src = URL.createObjectURL(blob)
      vid.controls = true
      document.body.appendChild(vid)
      const a = document.createElement('a')
      a.download = 'myvid.webm'
      a.href = vid.src
      a.className='downloadLink'
      a.textContent = 'download the video'
      a.onclick=()=>{
        open(vid.src)
      }
      document.body.appendChild(a)
    }
  
    if(stage2) Draw()
    if(!stage2){
      Draw()
      if(export_base) setTimeout(()=>startRecording(), initialDelay)
    }

  </script>
</html>

