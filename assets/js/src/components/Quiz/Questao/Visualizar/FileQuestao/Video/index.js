import React, { useEffect } from 'react';
import VimeoPlayer from '@vimeo/player';
import { Video } from '../styles';

function video({url, questaoId, respostaOuExplicacao}) {

  useEffect(() => {
    const video = {
      url,
      // title: false,
      // byline: false,
      // loop: true,
      // pip: true,
      // portrait: false,
      // controls: false
      // transparent:true
      // background: true
      // responsive: true,
    };

    var videoPlayer = new VimeoPlayer(`video_${respostaOuExplicacao}_${questaoId}`, video);
    videoPlayer.setVolume(1);
  }, [url]);

  return (         
    <Video>
      <div className="cont-video" id={`video_${respostaOuExplicacao}_${questaoId}`}></div>
    </Video> 
  );
}

export default video;