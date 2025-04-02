import React, { useState } from "react";
import "./styles.css";
import requestMethods from "../../utils/enums/request.methods.js"
import {request} from "../../utils/remote/axios.js";

const AuthComponent = () => {
  const [registerOrLogin,SetRegisterOrLogin] = useState('register')
  const [step,setStep] = useState(3)
  const [data,setData] = useState({
    "first_name":"",
    "last_name":"",
    "place_of_birth":"",
    "date_of_birth":"",
    "street":"",
    "city":"",
    "country":"",
    "email":"",
    "password":""
  })
  const submit = async (e) =>{
    e.preventDefault()
    if(step<3){
      setStep(step+1);
    }else if(step==3){
      const response = await request({
        method:requestMethods.POST,
        route:"/api/v0.1/register",
        body:{
          data
        }
      })
      console.log(response)
    }
  }
  const back = (e) =>{
    e.preventDefault()
    setStep(step-1)
  }
  const handleChange = (e) =>{
    setData({
      ...data,
      [e.target.name]:e.target.value
    })
  }
  const switchRegLog = () =>{
    if(registerOrLogin=='register'){
      SetRegisterOrLogin('login')
    }else{
      SetRegisterOrLogin('register')
    }
  }
  return (
    <div className="container">
      <div className="login-box">
        <div className="left">
          <button>Google</button>
        </div>
        <div className="right">
          <div className="right-content">

            <div className="numbers">
              <div className={`number ${step==1?'selected-number':''}`} >1</div>Details<div className={`number ${step==2?'selected-number':''}`} >2</div>Address<div className={`number ${step==3?'selected-number':''}`} >3</div>Signup
            </div>
            <hr />
              <h3>Step {step}/3</h3>
              <h2>Details</h2>
              {
                registerOrLogin=="register"?
                step===1?
                <form onSubmit={submit}>
                <label htmlFor="first_name"> First Name<input type="text" placeholder="John" name="first_name" id="first_name" onChange={(e)=>handleChange(e)}required/></label>
               
                <label htmlFor="last_name"> Last Name<input type="text" placeholder="Doe" name="last_name" id="last_name" onChange={(e)=>handleChange(e)} required/></label>
                <label htmlFor="place_of_birth"> Place of birth<input type="text" placeholder="New York" name="place_of_birth" id="place_of_birth" onChange={(e)=>handleChange(e)} required/></label>
                <label htmlFor="date_of_birth"> Date of birth<input type="date" placeholder="1-1-1999" name="date_of_birth" id="place_of_birth" onChange={(e)=>handleChange(e)} required/></label>
                
                
                <div className="buttons"><button type="submit" >Next</button></div>
              </form>:
              step===2?
              <form onSubmit={submit}>
              <label htmlFor="first_name"> Street 1<input type="text" placeholder="John" name="street" id="first_name" onChange={(e)=>handleChange(e)}required/></label>
             
              <label htmlFor=""> Street 2<input type="text" placeholder="Doe"  id="" onChange={(e)=>handleChange(e)} required/></label>
              <label htmlFor="city"> City<input type="text" placeholder="New York" name="city" id="city" onChange={(e)=>handleChange(e)} required/></label>
              <label htmlFor="country"> Country<input type="text" placeholder="USA" name="country" id="country" onChange={(e)=>handleChange(e)} required/></label>
              
              <div className="buttons">
              
              <div className="buttons">
              <button className="back-button" onClick={(e)=>back(e)}>Back</button>
              <button type="submit" >Next</button>
              </div>
              </div>
              
            </form>
              :
              <form onSubmit={submit}>
              <label htmlFor="email"> Email<input type="email" placeholder="example@email.com" name="email" id="email" onChange={(e)=>handleChange(e)}required/></label>
             
              <label htmlFor="password"> Password<input type="password" placeholder="*******" name="password" id="password" onChange={(e)=>handleChange(e)} required/></label>
             
              <div className="buttons">
              <button className="back-button" onClick={(e)=>back(e)}>Back</button>
              <button type="submit" >Signup</button>
              </div>
            </form>
              :"here login"
              }
              
          
          {registerOrLogin=='register'?<p>Already have account? <span onClick={()=>switchRegLog()}>Login</span></p>:''}
          </div>
        </div>
      

      </div>
    </div>
  );
};

export default AuthComponent;