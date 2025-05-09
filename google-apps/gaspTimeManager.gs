/**
 * The time manager manages the time in your script. The timemanager keeps track of
 * the time spend and can tell you how much time is left.
 * 
 * This manager is usefull in background scripts that will be killed by apps script
 * after some time. Every time before starting an operation you can see if you can 
 * acquire time to perform the operation. If there is not enough time left you can 
 * exit the program gracefully. 
 * 
 * @param {object} spec - specification for the construction of the object 
 * @return {object} methods
 * 
 * @version 1.0
 */
// eslint-disable-next-line no-unused-vars
function gaspTimeManager(spec) {
  'use strict';

  const objectName = 'gaspTimeManager';
  const logManager = spec.logManager || console;

  const createdTime = new Date().getTime();
  let availableTime = spec.availableTime;

  /**
   * @returns {number} the available time in milliseconds 
   */
  const timeLeft = () => {
    return availableTime - (new Date().getTime() - createdTime);
  };

  /**
   * @param {number} milliseconds - number of milliseconds to acquire
   * @returns {boolean} true if enough time left; false otherwise
   */
  const acquireTime = (milliseconds) => {
    return timeLeft() > milliseconds;
  };

  logManager.log(`Created ${objectName}`);

  return Object.freeze({
    getObjectName: objectName,
    timeLeft: timeLeft,
    acquireTime: acquireTime
  });
}
