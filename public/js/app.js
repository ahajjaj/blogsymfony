// reading progress bar
window.addEventListener('scroll', () => {
  const progress = document.querySelector('.read-progress');
  if(!progress) return;
  const total = document.body.scrollHeight - window.innerHeight;
  const percent = (window.scrollY / total) * 100;
  progress.style.width = percent + '%';
});

// reading time estimate
function readingTime(text){
  const words = text.trim().split(/\s+/).length;
  return Math.ceil(words / 200);
}

document.addEventListener('DOMContentLoaded', () => {
  const article = document.querySelector('.article-content');
  if(article){
    const minutes = readingTime(article.innerText);
    const el = document.getElementById('read-time');
    if(el){
      el.textContent = minutes + ' min de lecture';
    }
  }

  // like button
  const likeBtn = document.querySelector('.like-btn');
  if(likeBtn){
    const slug = likeBtn.dataset.slug;
    const key = 'like_'+slug;
    if(localStorage.getItem(key)){
      likeBtn.classList.add('active');
    }
    likeBtn.addEventListener('click', () => {
      likeBtn.classList.toggle('active');
      if(likeBtn.classList.contains('active')){
        localStorage.setItem(key, '1');
      }else{
        localStorage.removeItem(key);
      }
    });
  }

  // dark mode toggle
  const toggle = document.getElementById('dark-toggle');
  if(toggle){
    const apply = (val) => {
      if(val){
        document.body.classList.add('dark-mode');
      }else{
        document.body.classList.remove('dark-mode');
      }
    };
    const saved = localStorage.getItem('dark');
    apply(saved === '1');
    toggle.checked = saved === '1';
    toggle.addEventListener('change', (e)=>{
      localStorage.setItem('dark', e.target.checked ? '1' : '0');
      apply(e.target.checked);
    });
  }
});
