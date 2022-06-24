<?
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  set_time_limit(600);
  $reduce=$_GET['reduce'];
  $data = file_get_contents("../".$_GET['i']);
  //echo $data;
  $t = explode("\nv ", $data);
  $a=[];
  forEach($t as $v){
    $a[]=explode("\n", $v);
  }
  array_shift($a);
  
  $verts=[];
  forEach($a as $c){
    $n=explode(' ', $c[0]);
    $l=[];
    forEach($n as $vert){
      $l[]=floatval($vert);
    }
    array_push($verts,$l);
  }
  //echo json_encode($verts)."\n";

  $t = explode("\nf ", $data);
  $a=[];
  forEach($t as $f){
    $a[]=explode("\n", $f);
  }
  array_shift($a);
  $faces=[];
  forEach($a as $c){
    $n=explode(' ', $c[0]);
    array_shift($n);
    $l=[];
    forEach($n as $face){
      $l[]=intval($face);
    }
    array_push($faces,$l);
  }
  //echo json_encode($faces)."\n";
  
  $polys=[];
  forEach($faces as $poly){
    $l=[];
    forEach($poly as $vertIndex){
      $l[]=$verts[$vertIndex-1];
    }
    array_push($polys, $l);
  }
  //echo json_encode($polys)."\n";

  for($i=0;$i<sizeof($polys); ++$i){
    $poly = $polys[$i];
    for($m=0;$m<sizeof($poly);++$m){
      $vert1=$poly[$m];
      $x1=$vert1[0];
      $y1=$vert1[1];
      $z1=$vert1[2];
      $d=0;
      for($j=0;$j<sizeof($polys);$j++){
        if($j !== $i){
          $poly2=$polys[$j];
          for($k=0;$k<sizeof($poly2);++$k){
            $vert2=$poly2[$k];
            $x2=$vert2[0];
            $y2=$vert2[1];
            $z2=$vert2[2];
            $d+=sqrt(($x2-$x1)*($x2-$x1)+($y2-$y1)*($y2-$y1)+($z2-$z1)*($z2-$z1));
          }
        }
      }
      $polys[$i][$m][]=$d;
    }
  }
  
  function sortfunc($a, $b){
    $sum1 = $sum2 = 0;
    forEach($a as $vert){
      $sum1 += $vert[3];
    }
    forEach($b as $vert){
      $sum2 += $vert[3];
    }
    return $sum1 - $sum2;
  }
  
  function newPolySortFunc($a, $b){
    
    $a1= atan2($a[0], $a[2]);
    $a2= atan2($b[0], $b[2]);
    return $a1 - $a2;
  }
  
  usort($polys, 'sortfunc');  
  
  $shared = [];
  for($i=0; $i<floor(sizeof($polys)*(1-$reduce/100)); ++$i){
    $shrd=[];
    $mind = 6e6;
    for($j=0;$j<sizeof($polys[$i]);++$j){
      if($i!=$j){
        $vert=$polys[$i][$j];
        if($vert[3]<$mind){
          $mind=$vert[3];
          $vertIdx=$j;
        }
      }
    }
    $killVert=$polys[$i][$vertIdx];
    
    for($j=0;$j<sizeof($polys);++$j){
      if($j != $i){
        $poly = $polys[$j];
        $shr=false;
        forEach($poly as $vert){
          if(
            $vert[0]==$killVert[0] &&
            $vert[1]==$killVert[1] &&
            $vert[2]==$killVert[2]
          ){
            $shr=true;
          }
        }
        if($shr) $shrd[]=$j;
      }
    }
    if(sizeof($shrd)>2) $shared[]=[$killVert, $shrd];
  }
  
  $rShape=[];
  for($i=0;$i<sizeof($polys);++$i){
    $keep=true;
    for($j=0;$j<sizeof($shared);++$j){
      for($k=0;$k<sizeof($shared[$j][1]);++$k){
        if($i==$shared[$j][1][$k]){
          $keep=false;
        }
      }
    }
    if($keep){
      $npoly=[];
      for($j=0;$j<sizeof($polys[$i]);++$j){
        $npoly[]=[$polys[$i][$j][0],$polys[$i][$j][1],$polys[$i][$j][2]];
      }
      $rShape[] = $npoly;
    }
  }
  
  
  for($i=0;$i<sizeof($shared);++$i){
    $newPoly=[];
    for($j=0;$j<sizeof($shared[$i][1]);++$j){
      $tPoly=$polys[$shared[$i][1][$j]];
      for($k=0;$k<sizeof($tPoly);++$k){
        $tVert=$tPoly[$k];
        if(
          $tVert[0]!=$shared[$i][0][0] ||
          $tVert[1]!=$shared[$i][0][1] ||
          $tVert[2]!=$shared[$i][0][2]
        ){
          $newPoly[]=[$tVert[0],$tVert[1],$tVert[2]];
        }
      }
    }
    usort($newPoly, 'newPolySortFunc');
    $rShape[]=$newPoly;
    //echo json_encode($newPoly) . "<br>";
  }
  
  //echo json_encode($shared)."<br>";

  echo json_encode($rShape);

/*
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

*/
?>
