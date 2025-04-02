import { BrowserRouter, Routes,Route } from 'react-router-dom'
import './App.css'
import AuthComponent from './components/AuthComponent'
import { MyContext } from './context/MyContext'
import Home from './components/Home'

function App() {


  return (
    <div className='app-component'>
      <BrowserRouter>
        <Routes>
          <Route path='/auth' element={<AuthComponent />} />
          <Route path='/home' element={<Home/>}/>
        </Routes>
      </BrowserRouter>
    </div>
  )
}

export default App
